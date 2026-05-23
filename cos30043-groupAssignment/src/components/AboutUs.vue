<template>
  <main class="about-page">
    <section class="about-hero">
      <p class="eyebrow">Who we are</p>
      <h1>Made by film people,<br>for film people.</h1>
      <p class="hero-desc">
        This is a student project from students at Swinburne University. We're a group of film lovers who wanted to create a review site for people who loves films as much as we do. 
      </p>
    </section>

<section class="about-body">
  <h2 class="section-label">The team</h2>
  <div class="row g-4"> 
    <div class="col-12 col-md-12 col-xxl-4" v-for="member in team" :key="member.initials">
      <div class="team-card">
        <div class="card-identity">
          <div class="avatar">{{ member.initials }}</div>
          <h3>{{ member.name }}</h3>
          <ul class="task-list">
            <li v-for="task in member.tasks" v-bind:key="task">{{ task }}</li>
          </ul>
        </div>
        <p class="about-text">{{ member.about }}</p>
      </div>
    </div>
  </div>
</section>


    <section class="inspo-quote">
      <h2 class="section-label">Retrospect</h2>
      <div class="mission">
        <p class="mission-quote">"What we built"</p>
        <p class="mission-body">
          Our team collaborated to design and build a film review site powered by the TMDB API. Able to browse a catalogue of movies, users can create an account to write reviews and save their favourite films. 
          Overall, we aimed to create a platform that film lovers can use to share their thoughts and discover new movies.
        </p>
      </div>
    </section>

  </main>
</template>

<script setup>
const team = [
  { initials: 'AL', 
    name: 'Anabelle', 
    tasks: ['UI and Mobile Responsive', 'Contact Us page', 'Film API', 'Advanced Feature - Analytics Dashboard'],
    about: 'Majoring in Software Development. Mostly reads and likes cryptic puzzles. Enjoys watching the footy but knows her team wont ever win (shes a saints supporter)'},
  { initials: 'SR', 
    name: 'Sofia', 
    tasks: ['UI and Mobile Responsive', 'About Us page', 'Review page and component'], 
    about: 'Majoring in Software Dev. Growing up I loved film and animation. Watching Ghibli and Pixar movies made me love and want to create art.'},
  { initials: 'AM', 
    name: 'Alessandra', 
    tasks: ['UI Team','Terms of Service page', 'Search & Filter','Routing'], 
    about: 'Majors in Software Design. She enjoys spending time playing games or bouldering, especially when she is able to do these activities with loved ones or friends' },
  { initials: 'CS', 
    name: 'Cole', 
    tasks: ['Account Settings', 'Account Deletion', 'Favourite Movies','Login/Sign Up'], 
    about: 'Im majoring in software dev, my favourite movie is fellowship of the ring, my fav animal is a panda and a hobby of mine is golf.' },
  { initials: 'TF', 
    name: 'Theodore', 
    tasks: ['Advanced Feature - Movie Recommendaiton Algorithm','Catalogue'], 
    about: 'Majors in Cyber Security and AI. I enjoy playing video games with friends and cooking various dishes for my family.' },
  { initials: 'MB', 
    name: 'Matt', 
    tasks: ['User dashboard', 'Database setup'], 
    about: 'Majors in Software Engineering. I enjoy music, rugby, footy and building things. I always find my weekends are taken up by one of these things.' },
]
</script>

<style scoped>
.about-page {
  background: var(--bg-primary);
}

/* HERO SECTION */
.about-hero {
  background: var(--accent);
  padding: 5rem 2.5rem;
  border-bottom: 8px solid var(--accent-deep);
  position: relative;
  overflow: hidden;
}

.about-hero p {
  color: var(--text-secondary);
}

/* Racing Stripe thingo */
.about-hero::after {
  content: "";
  position: absolute;
  top: 0;
  right: 15%;
  width: 60px;
  height: 100%;
  background: var(--bg-primary);
  /* Hide by default (mobile) */
  display: none; 
}

h1 {
  font-size: 3.5rem;
  font-family: freight-sans-pro-ultra, sans-serif;
  font-weight: 900;
  font-style: normal;
  letter-spacing: 0.03em;
  color: var(--text-primary) !important;
  line-height: 0.9;
  -webkit-text-stroke: 1px var(--accent-deep);
  text-shadow: 3px 3px 0px rgba(0,0,0,0.1);
}

p {
  font-family: libre-franklin, sans-serif;
  font-weight: 400;
  font-style: normal;
}

.hero-desc {
  font-size: 1rem;
  color: var(--accent-text-muted);
  line-height: 1.6;
  max-width: 560px;
}

/* TEAM GRID SECTION */
.about-body {
  padding: 3rem;
}

.section-label {
  font-size: 0.75rem;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--accent);
  border-left: 4px solid var(--accent);
  padding-left: 0.75rem;
  margin-bottom: 2rem;
}

/* TEAM CARDS */
.team-card {
  background: var(--bg-surface);
  padding: 2rem 1.5rem;
  text-align: center;
  transition: transform 0.2s ease;
  display: flex;
  flex-direction: column; 
  /* align-items: center; */
}

.avatar {
  width: 60px;
  height: 60px;
  background: var(--accent);
  color: var(--bg-dark);
  font-weight: 900;
  margin: 0 auto 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.team-card h3 {
  color: var(--accent);
  margin-bottom: 1rem;
}

.task-list {
  list-style: none;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
  width: 75%;
  margin: 0 auto 1rem; 
}

.task-list li {
  font-size: 0.7rem;
  color: var(--text-secondary);
  background: rgba(255, 255, 255, 0.05);
  padding: 4px 8px;
  border: 1px solid var(--border-subtle);
}

p.about-text {
  color: var(--text-secondary);
}

/* MISSION SECTION */
.mission {
  border-left: 4px solid var(--accent);
  background: var(--bg-surface);
  padding: 2rem;
  margin-top: 2rem;
}

.mission-quote {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0 0 0.75rem;
}

.mission-body {
  font-size: 0.9rem;
  color: var(--text-secondary);
  line-height: 1.7;
  margin: 0;
}


@media (min-width: 768px) and (max-width: 1459px) {
  .team-card {
    flex-direction: row;
    text-align: left;
    align-items: flex-start;
    gap: 2rem;
  }
  .card-identity {
    flex: 0 0 45%;
  }

  .about-text {
    flex: 1;
    margin: 0;
    padding-top: 10px;
  }

  .task-list {
    width: 100%;
    margin: 0;
  }

  .avatar {
    margin: 0 0 1rem 0;
  }
}

@media (min-width: 1460px) {
  .team-card {
    flex-direction: column;
    text-align: center;
    height: 100%; /* Keeps cards same height in a row */
  }
  .avatar {
    margin: 0 auto 1rem;
  }
  .task-list {
    width: 75%;
    margin: 0 auto 1rem;
  }
}

/* hero stripe thingy display in bigger screens */
@media (min-width: 768px) {
  .about-hero::after {
    display: block;
  }
}

</style>