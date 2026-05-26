<template>
    <section class="policies-page">
        <div class="container">
            <div class="row">
                <div class="col-3" id="links">
                    <h3><router-link to="/policies/terms-and-conditions">Terms and Conditions</router-link></h3>
                    <h3><router-link to="/policies/privacy-policy">Privacy Policy</router-link></h3>
                </div>
                <div class="col-9" id="content">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

@media (max-width: 360px) {

    .policies-page {
        background-color: var(--bg-primary);
        
    }

    .policies-page #content {
        margin-top: 100px;
        margin-bottom: 40px;
        border: 2px solid var(--border-subtle);
        padding-left: 15px;
        padding-top: 10px;
        border-radius: 10px;
        background-color: var(--bg-form);
    }

    .policies-page a {
        display: block;
        text-decoration: none;
        color: var(--text-primary);
        font-size: 11px;

        border: 2px solid var(--border-subtle);
        border-radius: 10px;
        background-color: var(--bg-form);

        padding: 2px;
        margin-top: 5px;
    }

    .policies-page #links:first-child {
        margin-top: 100px;
    }

    .policies-page a.router-link-active {
        font-weight: bold;
    }

    .policies-page h3 {
        margin: 0;
        padding: 0;
    }
}


@media (min-width: 361px) {
    .policies-page {
        background-color: var(--bg-primary);
        
    }

    .policies-page #content {
        margin-top: 20px;
        margin-bottom: 40px;
        border: 2px solid var(--border-subtle);
        padding-left: 15px;
        padding-top: 10px;
        border-radius: 10px;
        background-color: var(--bg-form);
    }

    .policies-page a {
        text-decoration: none;
        color: var(--text-primary);
        font-size: 18px;

        border: 2px solid var(--border-subtle);
        border-radius: 10px;
        background-color: var(--bg-form);

        margin-top: 10px;
        padding: 5px;
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