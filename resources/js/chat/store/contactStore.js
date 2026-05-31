import {defineStore} from 'pinia';
import {ref} from 'vue';
import axios from 'axios';
import {useConversationsStore} from "./conversations";
import Swal from 'sweetalert2';

export const useContactStore = defineStore('contact', () => {
    const lists = ref([]);
    const loading = ref(false);
    const page = ref(1); // Start index for pagination
    const lastPage = ref(false); // Indicates if all pages have been loaded
    const search = ref(''); // Search query

    const fetchContacts = async (reset = false) => {
        if (loading.value || (lastPage.value && !reset)) return;

        loading.value = true;

        try {
            let url = `${window.API_URL}/get-chat/user/list`;

            if (reset) {
                page.value = 1;
                lists.value = []; // Clear existing list when resetting
                lastPage.value = false; // Reset lastPage flag
            }

            const response = await axios.get(url, {
                params: {
                    page: page.value,
                    search: search.value,
                },
            });

            const data = response.data.data || [];

            if (data.length > 0) {
                lists.value = [...lists.value, ...data];
                page.value += 1; // Update `page` based on received data length

                if (response.data.last_page === response.data.current_page) {
                    lastPage.value = true; // No more data to load
                }
            } else {
                if (response.data.last_page === response.data.current_page) {
                    lastPage.value = true; // No more data to load
                }
            }
        } catch (error) {
            // console.error('Error loading contacts:', error);
        } finally {
            loading.value = false; // Reset loading state
        }
    };

    return {lists, loading, page, lastPage, search, fetchContacts};
});
