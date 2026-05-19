<script setup>
import Review from './components/Review.vue'
import AboutUs from './components/AboutUs.vue'
import Catalogue from './components/Catalogue.vue'
import ContactUs from './components/ContactUs.vue'

import { useTheme } from './js/Theme.js';

const { isDark, toggleTheme } = useTheme();
</script>

<template>

  <div>
    <nav class="account-nav">
      <div class="nav-links">
        <router-link to="/films">Films</router-link> |
        <router-link to="/account">Account</router-link> |
        <router-link to="/lists">List</router-link> |
        <router-link to="/about-us">About Us</router-link> |
        <router-link to="/contact-us">Contact Us</router-link> 
      </div>
    </nav>

    <router-view />
  </div>

  <div>
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-4">
            <h3>Get to Know Us!</h3>
            <div class="footer-links">
              <h6><router-link to="/about-us">About Us</router-link></h6>
              <h6><router-link to="/contact-us">Contact Us</router-link></h6>
              <h6><router-link to="/policies">Policies</router-link></h6>
            </div>
          </div>
          <div class="col-4">
            <h3>Accounts</h3>
            <div class="footer-links">
              <h6><router-link to="/account">Your Account</router-link></h6>
              <h6><router-link to="/login">Login</router-link></h6>
              <h6><router-link to="/sign-up">Create an Account</router-link></h6>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>


  <!-- Temporary Toggle Button -->
    <button @click="toggleTheme" class="theme-toggle">
      <span class="toggle-icon"> 
        {{ isDark ? '☀️' : '🌙' }}
      </span>
      <span class="toggle-text">
        {{ isDark ? 'Light' : 'Dark' }} Mode
      </span>
    </button>
</template>
