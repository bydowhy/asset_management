import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

export function useFlashToast() {
    const page = usePage();

    watch(
        () => (page.props.flash as any),
        (flash) => {
            if (!flash) return;

            if (flash.success) {
                toast.success(flash.success, {
                    description: 'Perubahan telah disimpan.',
                    duration: 4000,
                });
            }
            if (flash.error) {
                toast.error(flash.error, { duration: 6000 });
            }
            if (flash.warning) {
                toast.warning(flash.warning, { duration: 5000 });
            }
            if (flash.info) {
                toast.info(flash.info, { duration: 4000 });
            }
        },
        { immediate: true, deep: true }
    );
}