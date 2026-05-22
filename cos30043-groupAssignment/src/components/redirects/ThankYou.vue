<template>
  <main class="thank-you">
    <h1>Thank you</h1>
    <p>We will get you shortly</p>
    <button @click="$router.push('/films')">Check out your favourite films</button>
  </main>
</template>
<style>
.thank-you {
    background-color: var(--bg-primary);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    text-align: center;
    color: var(--text-primary);

    button{
    margin-top: 20px;
    padding: 10px 20px;
    background-color: var(--accent);
    color: var(--bg-dark);
    border: none;
    font-style: bold;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
    }
}
</style>