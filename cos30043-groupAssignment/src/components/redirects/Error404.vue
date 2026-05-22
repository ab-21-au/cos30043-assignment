<template>
<main class="errorPage">
    <h1>404 - Page Not Found</h1>
    <img src="../../assets/error-placeholder.jpg" alt="Error Image">
    <h4>You have tried accessing a page that doesn't exist.</h4>
    <button @click="$router.push('/films')">Go back to the main page!</button>
</main>
</template>
<style>
@media (max-width: 1024px) {
    img{
        width: 200px;
        height: auto;
        margin: 20px 0;
        border-radius: 10px;
    }
    .errorPage {
        background-color: var(--bg-primary);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        text-align: center;
        color: var(--text-primary);
    }
}

.errorPage {
    background-color: var(--bg-primary);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    text-align: center;
    padding: 10%;
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