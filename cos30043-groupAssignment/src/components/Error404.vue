<template>
<main class="errorPage">
    <h1>404 - Page Not Found</h1>
    <img src="../assets/error-placeholder.jpg">
    <h4>You have tried accessing a page that doesn't exist.</h4>
    <p>Head back to our amazing main page!</p>
</main>
</template>
<style>
@media (max-width: 1024px) {
    img{
        width: 200px;
        height: auto;
        margin: 20px 0;
        border-radius: 10px;
        filter: var(--img-inverse);
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
}


</style>