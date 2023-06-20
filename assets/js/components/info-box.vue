<template>
    <l-control
        position="topleft"
    >
        <div class="card" data-bs-theme="dark">
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
                    :base-layers="baseLayers"
                    @change-baselayer="$emit('change-baselayer', $event)"
                />
                <category-selector
                    :selected="selected"
                    :tree="tree"
                    :categories="categories"
                    @toggle-category="$emit('toggle-category', $event)"
                />
            </div>
        </div>
    </l-control>
</template>

<script>
import { LControl } from '@vue-leaflet/vue-leaflet';
import FontAwesomeIcon from '@/services/fontawesome-services';
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
    props: {
        baseLayers: {
            type: Map,
            required: true,
        },
        categories: {
            type: Map,
            required: true,
        },
        tree: {
            type: Map,
            required: true,
        },
        selected: {
            type: Set,
            required: true,
        },
    },
    data() {
        return {
            collapsed: false,
        };
    },
};
</script>

<style lang="scss" module>
.component :global {
    width: 360px;
    max-height: 500px;
}
</style>
