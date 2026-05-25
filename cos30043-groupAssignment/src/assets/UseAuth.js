import { reactive, computed } from 'vue';

// Initialize state from localStorage so it survives page refreshes
const state = reactive({
    username: localStorage.getItem('auth_username') || null,
});

export function useAuth() {
    // Read-only getter to check if user is logged in
    const isAuthenticated = computed(() => !!state.username);

    // Read-only getter for the username
    const username = computed(() => state.username);

    // Function to log the user in
    const login = (username) => {
        state.username = username;
        localStorage.setItem('auth_username', username);
    };

    // Function to log the user out
    const logout = () => {
        state.username = null;
        localStorage.removeItem('auth_username');
    };

    return {
        username,
        isAuthenticated,
        login,
        logout
    };
}