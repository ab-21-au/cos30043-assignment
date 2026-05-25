import { reactive, computed } from 'vue';

// Initialize state from localStorage so it survives page refreshes
const state = reactive({
    username: localStorage.getItem('auth_username') || null,
    accountId: localStorage.getItem('auth_account_id') || null,
    isRestoringSession: false,
});

let restoreSessionPromise = null;

const getApiUrl = (endpoint) => import.meta.env.DEV
    ? `/api/${endpoint}`
    : `${import.meta.env.BASE_URL}api/${endpoint}`;

const clearAuthState = () => {
    state.username = null;
    state.accountId = null;
    localStorage.removeItem('auth_username');
    localStorage.removeItem('auth_account_id');
};

export function useAuth() {
    // Read-only getter to check if user is logged in
    const isAuthenticated = computed(() => !!state.username);

    // Read-only getter for the username
    const username = computed(() => state.username);

    // Read-only getter for the account id
    const accountId = computed(() => state.accountId);

    const isRestoringSession = computed(() => state.isRestoringSession);

    // Function to log the user in
    const login = (username, accountId = state.accountId) => {
        state.username = username;
        localStorage.setItem('auth_username', username);

        state.accountId = accountId;
        if (accountId) {
            localStorage.setItem('auth_account_id', accountId);
        } else {
            localStorage.removeItem('auth_account_id');
        }
    };

    const restoreSession = async () => {
        if (restoreSessionPromise) {
            return restoreSessionPromise;
        }

        state.isRestoringSession = true;

        restoreSessionPromise = fetch(getApiUrl('update_profile.php'), {
            method: 'GET',
            credentials: 'include',
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    login(result.username, result.account_id);
                    return true;
                }

                clearAuthState();
                return false;
            })
            .catch(error => {
                console.error('Unable to restore auth session:', error);
                return false;
            })
            .finally(() => {
                state.isRestoringSession = false;
                restoreSessionPromise = null;
            });

        return restoreSessionPromise;
    };

    // Function to log the user out
    const logout = async () => {
        clearAuthState();

        try {
            await fetch(getApiUrl('signout.php'), {
                method: 'POST',
                credentials: 'include',
            });
        } catch (error) {
            console.error('Unable to clear server session:', error);
        }
    };

    return {
        username,
        accountId,
        isRestoringSession,
        isAuthenticated,
        login,
        restoreSession,
        logout
    };
}
