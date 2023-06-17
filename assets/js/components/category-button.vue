<template>
    <div class="col-4">
        <button
            :class="['btn', selected ? 'btn-primary' : 'btn-outline-primary']"
            @click="$emit('toggle-category', id)"
        >
            {{ name }}
        </button>
    </div>
</template>

<script>
export default {
    name: 'CategoryButton',
    props: {
        id: {
            type: Number,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        selected: {
            type: Boolean,
            required: true,
        },
    },
};
</script>
