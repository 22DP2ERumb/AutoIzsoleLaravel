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
      <h1>About Autoizsole</h1>
      <p>Your trusted platform for transparent, competitive car auctions since 2018.</p>
    </section>

    <!-- Mission Section -->
    <section class="mission">
      <div class="mission-content">
        <h2>Our Mission</h2>
        <p>
          At Autoizsole, we bridge the gap between sellers and buyers with a fair auction system. No hidden fees, just real deals.
        </p>
      </div>
      <img 
        src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
        alt="Luxury cars lined up"
        class="mission-image"
      />
    </section>

    <!-- Team Section -->
    <section class="team">
      <h2>Meet the Team</h2>
      <div class="team-grid">
        <div v-for="member in team" :key="member.name" class="team-card">
          <img :src="member.image" :alt="member.name" />
          <h3>{{ member.name }}</h3>
          <p class="role">{{ member.role }}</p>
          <p class="bio">{{ member.bio }}</p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta">
      <h2>Ready to Bid?</h2>
      <button class="cta-button">Join an Auction</button>
    </section>
  </div>
</template>

<style scoped>
/* Global Styling */
.about-us {
  font-family: 'Arial', sans-serif;
  color: #333;
}

/* Hero Section */
.hero {
  text-align: center;
  padding: 60px 20px;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
  border-radius: 8px;
  margin-bottom: 40px;
}
.hero h1 {
  font-size: 2.5rem;
  margin-bottom: 16px;
  color: #2c3e50;
}
.hero p {
  font-size: 1.2rem;
  color: #7f8c8d;
}

/* Mission Section */
.mission {
  display: flex;
  align-items: center;
  gap: 40px;
  margin-bottom: 60px;
  flex-wrap: wrap;
}
.mission-content {
  flex: 1;
  min-width: 300px;
}
.mission h2 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #2c3e50;
}
.mission p {
  font-size: 1.1rem;
  line-height: 1.6;
}
.mission-image {
  flex: 1;
  min-width: 300px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Team Section */
.team {
  text-align: center;
  margin-bottom: 60px;
}
.team h2 {
  font-size: 2rem;
  margin-bottom: 40px;
  color: #2c3e50;
}
.team-grid {
  display: flex;
  justify-content: center;
  gap: 30px;
  flex-wrap: wrap;
}
.team-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  width: 280px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s;
}
.team-card:hover {
  transform: translateY(-5px);
}
.team-card img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 16px;
}
.team-card h3 {
  margin: 10px 0;
  font-size: 1.3rem;
}
.team-card .role {
  color: #e74c3c;
  font-weight: bold;
  margin-bottom: 10px;
}
.team-card .bio {
  color: #7f8c8d;
  font-size: 0.9rem;
}

/* CTA Section */
.cta {
  text-align: center;
  padding: 40px 20px;
  background: #2c3e50;
  color: white;
  border-radius: 8px;
}
.cta h2 {
  font-size: 2rem;
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
  transition: background 0.3s;
}
.cta-button:hover {
  background: #c0392b;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .mission {
    flex-direction: column;
  }
  .mission-image {
    order: -1;
  }
}
</style>