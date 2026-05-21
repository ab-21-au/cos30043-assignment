<script setup>
import { ref, Transition } from 'vue'
import { useTheme } from './js/Theme.js';

const { isDark, hamburgerIcon, crossIcon, logo, toggleTheme } = useTheme();

const isOpen = ref(false)

</script>

<template>

  <div>
    <div class="guest-nav">
      
      <img v-if="isOpen" @click="isOpen = !isOpen" id="menu" :src="crossIcon">
      <img v-else @click="isOpen = !isOpen" id="menu" :src="hamburgerIcon">
      
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
            <router-link to="/login">Login</router-link>
            <router-link to="/sign-up">Sign Up</router-link> 
          </nav>
        </aside>
      </transition>
    </div>

    <router-view />
    
  </div>

  <!-- <div> -- NAV FOR ACCOUNTS, CAN USE V-IF/ELSE STATEMENT TO JUST SWITCH LINKS FROM ABOVE
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
  </div> -->

  <div>
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-sm-12">
            <img id="logo" :src="logo">
          </div>
          <div class="col-lg-4 col-sm-6">
            <h3>Get to Know Us!</h3>
            <nav>
              <h6><router-link to="/about-us">About Us</router-link></h6>
              <h6><router-link to="/contact-us">Contact Us</router-link></h6>
              <h6><router-link to="/policies">Policies</router-link></h6>
            </nav>
          </div>
          <div class="col-lg-4 col-sm-6">
            <h3>Accounts</h3>
            <nav>
              <h6><router-link to="/account">Your Account</router-link></h6>
              <h6><router-link to="/login">Login</router-link></h6>
              <h6><router-link to="/sign-up">Create an Account</router-link></h6>
            </nav>
          </div>
        </div>
      </div>
    </footer>
  </div>

</template>
