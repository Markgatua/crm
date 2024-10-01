<script setup>
import ToastListItem from "@/OtherComponents/ToastListItem.vue";
import {onUnmounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/vue3";
import toast from "@/Stores/toast"

const page = usePage();

let removeFinishEventListener = Inertia.on("finish", () => {
    if (page.props.flash.success) {
        toast.add({
            message: page.props.flash.success,
        });
    }
    if (page.props.flash.error) {
        toast.add({
            message: page.props.flash.error,
        });
    }
});

onUnmounted(() => removeFinishEventListener());

function remove(index) {
    toast.remove(index);
}
</script>
<template>
    <TransitionGroup
        tag="div"
        enter-from-class="translate-x-full opacity-0"
        enter-active-class="duration-500"
        leave-active-class="duration-500"
        leave-to-class="translate-x-full opacity-0"
        class="fixed top-4 right-4 z-50 w-full max-w-xs space-y-4">
        <ToastListItem
            v-for="(item, index) in toast.items"
            :key="item.key"
            :type="page.props.flash"
            :message="item.message"
            :duration="8000"
            @remove="remove(index)"
        />
    </TransitionGroup>
</template>
