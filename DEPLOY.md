# Automatic Deploy — Oracle Cloud (Ubuntu 22.04, VM.Standard.E2.1.Micro)

Pushing to `main` triggers `.github/workflows/deploy.yml`, which:

1. **Builds** the API and frontend images on GitHub Actions (plenty of RAM —
   no OOM risk like building on the 1 GB VM).
2. **Pushes** them to the GitHub Container Registry (GHCR).
3. **SSHes** into the Oracle VM and runs `docker compose pull && up -d`
   (the VM only pulls pre-built images — it never builds).

The VM stays light because it just runs the containers (~80 MB total).

---

## 1. One-time VM setup (Ubuntu 22.04)

SSH into the instance (`ssh ubuntu@<PUBLIC_IP>`) and run:

```bash
# Install Docker + Compose plugin
curl -fsSL https://get.docker.com | sudo sh
sudo usermod -aG docker $USER
newgrp docker   # or log out/in

# Create the app directory (the workflow copies compose files here via SCP,
# so the repo does NOT need to be cloned on the VM).
mkdir -p ~/ecommerce-simulator
cd ~/ecommerce-simulator

# Create the production env file (NOT in git). Generate an APP_KEY first:
docker run --rm php:8.3-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"

cat > .env.prod <<'EOF'
APP_KEY=base64:PASTE_THE_GENERATED_KEY_HERE
APP_URL=http://<PUBLIC_IP>
DB_DATABASE=shopping_simulator
DB_USERNAME=shopping
DB_PASSWORD=change_this_password
GRAFANA_USER=admin
GRAFANA_PASSWORD=change_this_password
EOF
nano .env.prod   # paste the APP_KEY, set DB_PASSWORD and APP_URL
```

> The repo can stay **private** — the VM never clones it. CI copies
> `docker-compose.prod.yml` over SCP and pulls the images from GHCR.

---

## 2. Open the firewall — BOTH layers (Oracle gotcha)

Oracle blocks traffic in **two** places. You must open port 80 in both or
the site is unreachable.

### a) OCI Security List / NSG (cloud console)
In the OCI console → your VCN → Security List → **Add Ingress Rule**:
- Source CIDR `0.0.0.0/0`, IP Protocol `TCP`, Destination Port `80`.

### b) Instance iptables (Ubuntu ships a restrictive ruleset)
```bash
sudo iptables -I INPUT 6 -m state --state NEW -p tcp --dport 80 -j ACCEPT
sudo netfilter-persistent save     # persist across reboots
```
Verify with `sudo iptables -L INPUT --line-numbers` (the new ACCEPT for
dport 80 must come **before** the catch-all REJECT).

---

## 3. SSH key for GitHub Actions

On your machine, create a dedicated deploy key and authorize it on the VM:

```bash
ssh-keygen -t ed25519 -f deploy_key -N ""        # creates deploy_key + deploy_key.pub
ssh-copy-id -i deploy_key.pub ubuntu@<PUBLIC_IP>  # or append to ~/.ssh/authorized_keys
```

---

## 4. GitHub repository secrets

Repo → **Settings → Secrets and variables → Actions → New repository secret**:

| Secret | Value |
|---|---|
| `SSH_HOST` | VM public IP |
| `SSH_USER` | `ubuntu` |
| `SSH_KEY`  | contents of the **private** `deploy_key` (full file, including header/footer) |

> GHCR auth is automatic — the workflow uses the built-in `GITHUB_TOKEN`, so
> no registry secret is needed and the images can stay private.

---

## 5. First deploy

Push to `main` (or run the workflow manually via **Actions → Deploy to
Oracle Cloud → Run workflow**). After it finishes:

```
http://<PUBLIC_IP>      # the app
```

Migrations and catalog seeding run automatically on the API container's
first boot (the seeder is idempotent, so restarts won't duplicate data).

---

## Optional: monitoring (Prometheus + Grafana)

These are off by default (compose `monitoring` profile). To enable on the VM
(needs more RAM — not recommended on the 1 GB micro):

```bash
cd ~/ecommerce-simulator
docker compose --env-file .env.prod -f docker-compose.prod.yml --profile monitoring up -d
```
Also open ports 9090/3001 in both firewall layers if you want them exposed.

---

## Troubleshooting

- **Site unreachable**: almost always the firewall — re-check BOTH layers (§2).
- **`exec format error`**: image arch mismatch. This VM is AMD/`amd64`; the
  workflow already builds `linux/amd64`, so this shouldn't happen here.
- **Check containers on the VM**:
  ```bash
  cd ~/ecommerce-simulator
  docker compose --env-file .env.prod -f docker-compose.prod.yml ps
  docker compose --env-file .env.prod -f docker-compose.prod.yml logs api
  ```
- **Out of memory during anything**: confirm nothing is *building* on the VM
  (the pipeline builds in CI). `docker stats` should show ~80 MB total.
