<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h4>About</h4>
          <p>ShopSim - A shopping experience simulator without payment.</p>
        </div>
        <div class="footer-section">
          <h4>Links</h4>
          <ul>
            <li><router-link to="/">Products</router-link></li>
            <li><router-link to="/cart">Cart</router-link></li>
            <li><router-link to="/orders">Orders</router-link></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Support</h4>
          <ul>
            <li><a href="#">Help Center</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Privacy</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 ShopSim. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: #1f2937;
  color: #f3f4f6;
  padding: 3rem 0 1rem;
  margin-top: 4rem;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  margin-bottom: 2rem;
}

.footer-section h4 {
  font-size: 1rem;
  margin-bottom: 1rem;
  color: #fff;
}

.footer-section p {
  font-size: 0.875rem;
  line-height: 1.6;
}

.footer-section ul {
  list-style: none;
}

.footer-section li {
  margin-bottom: 0.5rem;
}

.footer-section a {
  color: #d1d5db;
  text-decoration: none;
  font-size: 0.875rem;
  transition: color 0.2s;
}

.footer-section a:hover {
  color: #fff;
}

.footer-bottom {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid #374151;
  font-size: 0.875rem;
  color: #9ca3af;
}

@media (max-width: 768px) {
  .footer-content {
    grid-template-columns: 1fr;
  }
}
</style>
