<template>
    <l-control
        position="topleft"
    >
        <div
            class="card"
            data-bs-theme="dark"
        >
            <div
                v-show="!collapsed"
                class="card-header row"
            >
                <div class="col">
                    <font-awesome-icon
                        icon="user"
                    />
                </div>
                <div class="col">
                    <font-awesome-icon
                        icon="magnifying-glass"
                    />
                </div>
                <div class="col">
                    <button
                        class="btn btn-secondary"
                        @click="collapsed = !collapsed"
                    >
                        <font-awesome-icon
                            icon="compress-alt"
                        />
                    </button>
                </div>
            </div>
            <div
                v-show="collapsed"
                class="card-body"
            >
                <button
                    class="btn btn-secondary"
                    @click="collapsed = !collapsed"
                >
                    <font-awesome-icon
                        icon="expand-alt"
                    />
                </button>
            </div>
            <div
                v-show="!collapsed"
                :class="[$style.component, 'card-body', 'overflow-scroll']"
            >
                <map-selector
                    v-show="1 < baseLayers.size"
                />
                <category-selector />
            </div>
        </div>
    </l-control>
</template>

<script>
import { mapState } from 'pinia';
import { LControl } from '@vue-leaflet/vue-leaflet';
import FontAwesomeIcon from '@/services/fontawesome-services';
import { useMapStore } from '@/Store/MapStore';
import MapSelector from '@/components/map-selector.vue';
import CategorySelector from '@/components/category-selector';

export default {
    name: 'InfoBox',
    components: {
        CategorySelector,
        MapSelector,
        LControl,
        FontAwesomeIcon,
    },
    data() {
        return {
            collapsed: false,
        };
    },
    computed: {
        ...mapState(useMapStore, ['baseLayers']),
    },
};
</script>

<style lang="scss" module>
.component :global {
    width: 360px;
    max-height: 500px;
}
</style>
