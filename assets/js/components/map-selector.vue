<template>
    <div>
        <button
            v-for="[id, layer] in baseLayers"
            :key="id"
            class="btn btn-primary"
            @click="$emit('change-baselayer', layer.id)"
        >
            {{ layer.name }}
        </button>
    </div>
</template>

<script>
export default {
    name: 'MapSelector',
    props: {
        baseLayers: {
            type: Map,
            required: true,
        },
    },
};
</script>
