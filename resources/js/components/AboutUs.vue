<script setup>
const team = [
  {
    name: "Alex Carter",
    role: "Founder & CEO",
    bio: "Car enthusiast with 10+ years in automotive sales.",
    image: "https://randomuser.me/api/portraits/men/32.jpg",
  },
  {
    name: "Jamie Rivera",
    role: "Lead Auction Specialist",
    bio: "Expert in luxury vehicle valuations.",
    image: "https://randomuser.me/api/portraits/women/44.jpg",
  },
  {
    name: "Taylor Smith",
    role: "Customer Relations",
    bio: "Ensures seamless bidding experiences.",
    image: "https://randomuser.me/api/portraits/men/75.jpg",
  },
];
</script>

<template>
  <div class="about-us">
    <!-- Hero Section -->
    <section class="hero">
      <div class="container">
        <h1>About Autoizsole</h1>
        <p>Your trusted platform for transparent, competitive car auctions since 2018.</p>
      </div>
    </section>

    <!-- Mission Section -->
    <section class="mission">
      <div class="container">
        <div class="mission-grid">
          <div class="mission-content">
            <h2>Our Mission</h2>
            <p>
              At Autoizsole, we bridge the gap between sellers and buyers with a fair auction system. No hidden fees, just real deals.
            </p>
          </div>
          <div class="mission-image-wrapper">
            <img 
              src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
              alt="Luxury cars lined up"
              class="mission-image"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="team">
      <div class="container">
        <h2>Meet the Team</h2>
        <div class="team-grid">
          <div v-for="member in team" :key="member.name" class="team-card">
            <img :src="member.image" :alt="member.name" loading="lazy" />
            <h3>{{ member.name }}</h3>
            <p class="role">{{ member.role }}</p>
            <p class="bio">{{ member.bio }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta">
      <div class="container">
        <h2>Ready to Bid?</h2>
        <button class="cta-button">Join an Auction</button>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Global Styling */
.about-us {
  font-family: 'Arial', sans-serif;
  color: #333;
  line-height: 1.6;
}

.container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  box-sizing: border-box;
}

/* Hero Section */
.hero {
  text-align: center;
  padding: 80px 0;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
  margin-bottom: 40px;
}

.hero h1 {
  font-size: clamp(2rem, 5vw, 2.8rem);
  margin-bottom: 16px;
  color: #2c3e50;
  line-height: 1.2;
}

.hero p {
  font-size: clamp(1rem, 2.5vw, 1.3rem);
  color: #7f8c8d;
  max-width: 700px;
  margin: 0 auto;
}

/* Mission Section */
.mission {
  padding: 60px 0;
  margin-bottom: 40px;
}

.mission-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 40px;
  align-items: center;
}

.mission h2 {
  font-size: clamp(1.5rem, 3vw, 2rem);
  margin-bottom: 20px;
  color: #2c3e50;
}

.mission p {
  font-size: 1.1rem;
}

.mission-image-wrapper {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  aspect-ratio: 16/9;
}

.mission-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Team Section */
.team {
  padding: 60px 0;
  margin-bottom: 40px;
  text-align: center;
}

.team h2 {
  font-size: clamp(1.5rem, 3vw, 2rem);
  margin-bottom: 40px;
  color: #2c3e50;
}

.team-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  justify-content: center;
}

.team-card {
  background: white;
  border-radius: 8px;
  padding: 25px 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s, box-shadow 0.3s;
}

.team-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.team-card img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  margin: 0 auto 16px;
  display: block;
}

.team-card h3 {
  margin: 10px 0;
  font-size: 1.3rem;
}

.team-card .role {
  color: #e74c3c;
  font-weight: bold;
  margin-bottom: 10px;
  font-size: 0.95rem;
}

.team-card .bio {
  color: #7f8c8d;
  font-size: 0.9rem;
}

/* CTA Section */
.cta {
  text-align: center;
  padding: 60px 0;
  background: #2c3e50;
  color: white;
}

.cta h2 {
  font-size: clamp(1.5rem, 3vw, 2rem);
  margin-bottom: 20px;
}

.cta-button {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 12px 30px;
  font-size: 1.1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
}

.cta-button:hover {
  background: #c0392b;
  transform: translateY(-2px);
}

/* Responsive Adjustments */
@media (max-width: 600px) {
  .hero {
    padding: 60px 0;
  }
  
  .mission, .team, .cta {
    padding: 50px 0;
  }
  
  .team-grid {
    grid-template-columns: 1fr;
    max-width: 350px;
    margin: 0 auto;
  }
}
</style>