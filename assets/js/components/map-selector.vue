<template>
    <div class="row">
        <div
            v-for="[id, layer] in baseLayers"
            :key="id"
            class="col text-center"
        >
            <button
                :class="['btn', currentLayer.id === id ? 'btn-primary' : 'btn-outline-primary']"
                @click="changeBaseLayer(layer.id)"
            >
                {{ layer.name }}
            </button>
        </div>
        <hr class="mt-3">
    </div>
</template>

<script>
import { mapActions, mapState } from 'pinia';
import { useMapStore } from '@/Store/MapStore';

export default {
    name: 'MapSelector',
    computed: {
        ...mapState(useMapStore, ['baseLayers', 'currentLayer']),
    },
    methods: {
        ...mapActions(useMapStore, ['changeBaseLayer']),
    },
};
</script>
