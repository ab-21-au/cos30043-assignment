<template>
    <section class="policies-page">
        <div class="container">
            <div class="row">
                <div id="links">
                    <h3><router-link to="/policies/terms-and-conditions">Terms and Conditions</router-link></h3>
                    <h3><router-link to="/policies/privacy-policy">Privacy Policy</router-link></h3>
                </div>
                <div id="content">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

.policies-page {
    background-color: var(--bg-primary);
}

.policies-page #content {
    margin: 20px auto 40px;
    border: 2px solid var(--border-subtle);
    padding-left: 15px;
    padding-top: 10px;
    border-radius: 10px;
    min-width: 150px;
    max-width: calc(100% - 40px);
    width: auto;
    box-sizing: border-box;
    background-color: var(--bg-form);
}

.policies-page a {
    display: block;
    text-decoration: none;
    color: var(--text-primary);
    font-size: 13px;
    max-width: 200px;

    border: 2px solid var(--border-subtle);
    border-radius: 10px;
    background-color: var(--bg-form);

    padding: 10px;
    margin-top: 5px;
}

.policies-page #links{
    display: flex;
    justify-content: center;
    gap: 2%;
}

.policies-page #links:first-child {
    margin-top: 50px;
}

.policies-page a.router-link-active {
    font-weight: bold;
    color: var(--bg-input);
    background: var(--accent)
}

.policies-page h3 {
    margin: 0;
    padding: 0;
    text-align: left;
}


@media (min-width: 768px) {
    .policies-page {
        background-color: var(--bg-primary); 
    }

    .policies-page #content {
        margin: 5px auto 40px;
        border: 2px solid var(--border-subtle);
        padding-left: 15px;
        padding-top: 10px;
        border-radius: 10px;
        max-width: calc(100% - 40px);
        background-color: var(--bg-form);
    }

    .policies-page a {
        text-decoration: none;
        color: var(--text-primary);
        font-size: 18px;
        border: 2px solid var(--border-subtle);
        border-radius: 10px;
        background-color: var(--bg-form);
        max-width: 400px;

        margin-top: 10px;
        padding: 10px;
    }

    .policies-page #links:first-child {
        margin-top: 20px;
    }

    .policies-page a.router-link-active {
        font-weight: bold;
    }

    .policies-page h3 {
        margin: 0;
        margin-bottom: 30px;
        padding: 0;
        text-align: left;
    }
}


</style>
