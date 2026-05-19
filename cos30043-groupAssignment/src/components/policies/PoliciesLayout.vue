<template>
    <section class="policies-page">
        <div class="container-fluid">
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

.policies-page {
    background-color: var(--bg-primary);
    
}

#content {
    margin-top: 10px;
    margin-bottom: 40px;
    border: 2px solid var(--border-subtle);
    padding-left: 15px;
    padding-top: 10px;
    border-radius: 10px;
    background-color: var(--bg-form);
}

a {
    text-decoration: none;
    color: var(--text-primary);
    font-size: 18px;

    border: 2px solid var(--border-subtle);
    border-radius: 10px;
    background-color: var(--bg-form);

    padding: 5px;
}

a.router-link-active {
    font-weight: bold;
}


</style>