<template>
    <div class="row">
        <div
            v-for="[id, layer] in baseLayers"
            :key="id"
            class="col text-center"
        >
            <button class="btn btn-primary" @click="$emit('change-baselayer', layer.id)">{{ layer.name }}</button>
        </div>
        <hr>
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
