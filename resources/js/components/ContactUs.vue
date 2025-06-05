<script setup>
// No functionality needed for this design
</script>

<template>
  <div class="contact-container">
    <div class="contact-header">
      <h1>Get in Touch</h1>
      <p>We'd love to hear from you! Send us a message below.</p>
    </div>

    <div class="contact-content">
      <div class="contact-info">
        <div class="info-card">
          <div class="icon-wrapper">
            <svg viewBox="0 0 24 24">
              <path d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" />
            </svg>
          </div>
          <h3>Our Location</h3>
          <p>Bauskas Iela 53<br>Rīga</p>
        </div>

        <div class="info-card">
          <div class="icon-wrapper">
            <svg viewBox="0 0 24 24">
              <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
            </svg>
          </div>
          <h3>Phone Number</h3>
          <p>+371 23960345</p>
        </div>

        <div class="info-card">
          <div class="icon-wrapper">
            <svg viewBox="0 0 24 24">
              <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
            </svg>
          </div>
          <h3>Email Address</h3>
          <p>contact@AutoIzsole.com</p>
        </div>
      </div>

      <form class="contact-form">
        <div class="form-row">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" placeholder="John Doe">
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" placeholder="john@example.com">
          </div>
        </div>

        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" placeholder="How can we help?">
        </div>

        <div class="form-group">
          <label for="message">Your Message</label>
          <textarea id="message" rows="5" placeholder="Type your message here..."></textarea>
        </div>

        <button class="submit-btn">Send Message</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.contact-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.contact-header {
  text-align: center;
  margin-bottom: 3rem;
}

.contact-header h1 {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.contact-header p {
  font-size: 1.1rem;
  color: #7f8c8d;
}

.contact-content {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 2rem;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.info-card {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.icon-wrapper {
  width: 50px;
  height: 50px;
  background: #3498db;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
}

.icon-wrapper svg {
  width: 24px;
  height: 24px;
  fill: white;
}

.info-card h3 {
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.info-card p {
  color: #7f8c8d;
  line-height: 1.5;
}

.contact-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #2c3e50;
  font-weight: 500;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #3498db;
}

.form-group textarea {
  resize: vertical;
  min-height: 120px;
}

.submit-btn {
  background: #3498db;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.submit-btn:hover {
  background: #2980b9;
}

@media (max-width: 768px) {
  .contact-content {
    grid-template-columns: 1fr;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>