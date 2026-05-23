
<template>
    <div>
        <h1>Privacy Policy</h1>
        <p id="last-update">Last updated: May 2026</p>

        <p>
            'Retrospect' is committed to providing quality services to you and this policy outlines our ongoing obligations to 
            you in respect of how we manage your Personal Information. <br>
            We have adopted the Australian Privacy Principles (APPs) contained 
            in the Privacy Act 1988 (Cth) (the Privacy Act). The NPPs govern the way in which we collect, use, disclose, store, secure 
            and dispose of your Personal Information. <br>
            A copy of the Australian Privacy Principles may be obtained from the website of The Office of the Australian 
            Information Commissioner at <a href="https://www.oaic.gov.au/">https://www.oaic.gov.au/</a>.
        </p>

        <h2>What is Personal Information and why do we collect it?</h2>
            <p>
                Personal Information is information or an opinion that identifies an individual. Examples of 
                Personal Information we collect includes names, email addresses, gender and date of birth. <br>
                This Personal Information is obtained via our Site's sign up page. We don't guarantee website 
                links or policy of authorized third parties. <br>
                We collect your Personal Information for the primary purpose of providing our services to you, and for allowing you to create and account.
                We also will track your movie reviews to provide account statistic and movie recommendations.<br>
                When we collect Personal Information we will, where appropriate and where possible, explain 
                to you why we are collecting the information and how we plan to use it.
            </p>

        <h2>Sensitive Information</h2>
            <p>
                Sensitive information is defined in the Privacy Act to include information or opinion about such things as 
                an individual's racial or ethnic origin, political opinions, membership of a political association, religious 
                or philosophical beliefs, membership of a trade union or other professional body, criminal record or health information.<br>
                Sensitive information will be used by us only:
                <ul>
                    <li>
                        For the primary purpose for which it was obtained
                    </li>
                    <li>
                        For a secondary purpose that is directly related to the primary purpose
                    </li>
                    <li>
                        With your consent; or where required or authorized by law.
                    </li>
                </ul>
            </p>

        <h2>Third Parties</h2>
            <p>
                Where reasonable and practicable to do so, we will collect your Personal Information only from you. 
                However, in some circumstances we may be provided with information by third parties. In such a case 
                we will take reasonable steps to ensure that you are made aware of the information provided to us by the third party.
            </p>

        <h2>Disclosure of Personal Information</h2>
            <p>
                Your Personal Information may be disclosed in a number of circumstances including the following:
                <ul>
                    <li>
                        Third parties where you consent to the use or disclosure; and
                    </li>
                    <li>
                        Where required or authorized by law.
                    </li>
                </ul>
            </p>
            
        <h2>Security of Personal Information</h2>
            <p>
                Your Personal Information is stored in a manner that reasonably protects it from misuse and loss and from 
                unauthorized access, modification or disclosure. <br>
                When your Personal Information is no longer needed for the purpose for which it was obtained, we will take 
                reasonable steps to destroy or permanently de-identify your Personal Information. However, most of the Personal 
                Information is or will be stored in client files which will be kept by us for a minimum of 7 years.
            </p>
            
        <h2>Access to your Personal Information</h2>
            <p>
                You may access the Personal Information we hold about you and to update and/or correct it, subject to 
                certain exceptions. If you wish to access your Personal Information, please contact us <router-link to="contact-us">here</router-link>. <br>
                'Retrospect' will not charge any fee for your access request, but may charge an administrative fee for 
                providing a copy of your Personal Information.<br>
                In order to protect your Personal Information we may require identification from you before releasing 
                the requested information.
            </p>
            
        <h2>Maintaining the Quality of your Personal Information</h2>
            <p>
                It is an important to us that your Personal Information is up to date. We  will  take reasonable 
                steps to make sure that your Personal Information is accurate, complete and up-to-date. If you 
                find that the information we have is not up to date or is inaccurate, please advise us as soon as 
                practicable so we can update our records and ensure we can continue to provide quality services to you.
            </p>
            
        <h2>Policy Updates</h2>
            <p>
                These Policies may change, but they will always be available on our Site, with a 'Last Updated' tag
            </p>
            
        <h2>Privacy Policy Complaints and Enquiries</h2>
            <p>
                If you have any questions or complaints regarding our Privacy Policy, you can contact us <router-link to="contact-us">here</router-link>.
            </p>  

    </div>
</template>


<style scoped>

@media (min-width: 320px) {
    #last-update {
        font-style: italic;
        color: var(--text-muted);
    }

    h1 {
        border-bottom: 1px solid var(--border-subtle);
        font-size: 22px;
    }

    h2 {
        font-size: 20px;
    }

    h1, h2 {
        color: var(--text-primary);
    }

    p, ul {
        color: var(--text-secondary);
        font-size: 14px;
    }
}

@media (min-width: 1024px) {
    #last-update {
        font-style: italic;
        color: var(--text-muted);
    }

    h1 {
        border-bottom: 1px solid var(--border-subtle);
        font-size: 30px;
    }

    h2 {
        font-size: 23px;
    }

    h1, h2 {
        color: var(--text-primary);
    }

    p, ul {
        color: var(--text-secondary);
    }
}

</style>

