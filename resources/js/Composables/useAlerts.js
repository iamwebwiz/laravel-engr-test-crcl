import {ref} from "vue";

const alerts = ref([]);

export default function useAlerts() {
    const removeAlert = (id) => {
        setTimeout(() => {
            alerts.value = alerts.value.filter(alert => alert.id !== id);
        }, 2500);
    };

    const addAlert = (alert) => {
        const id = (new Date()).valueOf();

        alerts.value.push({
            id: id,
            ...alert
        });

        removeAlert(id);

        if (alerts.value.length > 5) {
            alerts.value = alerts.value.slice(1);
        }
    };

    return {
        alerts,
        addAlert,
        removeAlert
    }
}
