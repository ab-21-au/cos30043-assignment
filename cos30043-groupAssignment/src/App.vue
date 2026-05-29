<script setup>
import { onMounted, ref, Transition } from 'vue'
import { useTheme } from './js/Theme.js';

import { useAuth } from './assets/UseAuth.js';

const { isDark, hamburgerIcon, crossIcon, logo, toggleTheme } = useTheme();

const auth = useAuth();

const isOpen = ref(false)

onMounted(() => {
  auth.restoreSession()
})

</script>

<template>

  <div>
    <div class="guest-nav">
      
      <img v-if="isOpen" @click="isOpen = !isOpen" id="menu" :src="crossIcon" alt="Navigation X">
      <img v-else @click="isOpen = !isOpen" id="menu" :src="hamburgerIcon" alt="Navigation Hamburger">
      
      <transition name="slide">
        <aside v-if="isOpen" class="nav-links">

          <!-- Temporary Toggle Button -->
          <button @click="toggleTheme" class="theme-toggle">
            <span class="toggle-icon"> 
              {{ isDark ? '☀️' : '🌙' }}
            </span>
            <span class="toggle-text">
              {{ isDark ? 'Light' : 'Dark' }} Mode
            </span>
          </button>

          <nav>
            <router-link to="/films">Films</router-link>
            <router-link to="/about-us">About Us</router-link>
            <router-link to="/contact-us">Contact Us</router-link>

            <template v-if="!auth.isAuthenticated.value">
              <router-link to="/login" >Login</router-link>
              <router-link to="/sign-up" >Sign Up</router-link> 
            </template>
            
            <template v-else>
              <router-link to="/account" >Account Summary</router-link>
              <router-link to="/account/settings" >Settings</router-link>
              <a href="#" @click.prevent="auth.logout(); isOpen = false">Sign Out</a>
            </template>
          </nav>
        </aside>
      </transition>
    </div>

    <main>
      <router-view />
    </main>

    
  </div>

  <div>
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-12 d-flex justify-content-center justify-content-lg-start">
            <img id="logo" :src="logo" alt="Retrospect Logo">
          </div>
          <div class="col-lg-4 col-6">
            <h1 id="footer-header">Get to Know Us!</h1>
            <nav>
              <h2><router-link to="/about-us">About Us</router-link></h2>
              <h2><router-link to="/contact-us">Contact Us</router-link></h2>
              <h2><router-link to="/policies">Policies</router-link></h2>
            </nav>
          </div>
          <div class="col-lg-4 col-6">
            <h1 id="footer-header">Accounts</h1>
            <nav>
              <h2><router-link to="/account">Your Account</router-link></h2>
              <h2><router-link to="/login">Login</router-link></h2>
              <h2><router-link to="/sign-up">Create an Account</router-link></h2>
            </nav>
          </div>
        </div>
      </div>
    </footer>
  </div>

</template>

