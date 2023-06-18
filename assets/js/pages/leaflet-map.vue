<template>
    <div :class="[$style.component]">
        <l-map
            ref="zumap"
            :zoom="mapConfig.defaultZoom"
            :center="mapConfig.defaultCenter"
            :crs="crs"
            :max-zoom="mapConfig.maxZoom"
            :options="{
                zoomControl: false,
                attributionControl: false
            }"
            @ready="mapReady"
        >
            <l-tile-layer
                v-bind="currentLayer"
                layer-type="base"
            />

            <l-geo-json
                :geojson="visibleMarkers"
                :options="{
                    pointToLayer: function(feature, latlng) {
                        const category = categories.get(feature.properties.category);
                        return category.label
                            ? L.marker(latlng, {
                                icon: L.divIcon({
                                    className: 'text-center text-nowrap w-auto label',
                                    html: '<div>' + feature.properties.name + '</div>',
                                })
                            })
                            : L.circleMarker(latlng, {
                                radius: 8,
                                color: category.color,
                                fillOpacity: category.label ? 0 : 1,
                                fill: category.label ? 0 : 1,
                            });
                    }
                }"
            />

            <info-box
                :base-layers="baseLayers"
                :categories="categories"
                :tree="tree"
                :selected="selectedCategories"
                @change-baselayer="changeBaseLayer"
                @toggle-category="toggleCategory"
            />

            <l-control-zoom
                position="bottomright"
            />
        </l-map>
    </div>
</template>

<script>
// import axios from 'axios';
// import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import {
    LMap, LControlZoom, LTileLayer, LGeoJson, LMarker,
} from '@vue-leaflet/vue-leaflet';
import InfoBox from '@/components/info-box';
import {
    fetchMapConfig, fetchMapBaseLayers, fetchMarkers, fetchCategories, fetchTree,
} from '@/services/map-services';

export default {
    name: 'LeafletMap',
    components: {
        LMarker,
        LGeoJson,
        LTileLayer,
        LMap,
        LControlZoom,
        InfoBox,
    },
    data() {
        return {
            url: '',
            currentZoom: 8,
            map: null,
            currentLayer: {},
            selectedCategories: new Set(),
        };
    },
    computed: {
        L() {
            return L;
        },
        crs() {
            return L.extend({}, L.CRS.Simple, {
                transformation: new L.transformation(this.mapConfig.scaleX ?? 1, this.mapConfig.offsetX ?? 0, this.mapConfig.scaleY ?? -1, this.mapConfig.offsetY ?? 0),
            });
        },
        mapConfig() {
            return fetchMapConfig();
        },
        baseLayers() {
            return fetchMapBaseLayers();
        },
        markers() {
            return fetchMarkers();
        },
        categories() {
            return fetchCategories();
        },
        tree() {
            return fetchTree();
        },
        visibleMarkers() {
            const selectedCategoriesSize = this.selectedCategories.size;
            const { currentZoom } = this;
            const currentLayerId = this.currentLayer.id;
            return this.markers.filter((marker) => {
                const category = this.categories.get(marker.properties.category);

                return marker.properties.baseLayer === currentLayerId
                && (
                    (
                        selectedCategoriesSize > 0 && this.selectedCategories.has(category.id)
                    )
                    || (
                        (category.label || selectedCategoriesSize === 0)
                        && currentZoom >= category.minZoom
                        && currentZoom <= category.maxZoom
                    )
                );
            });
        },
    },
    created() {
        this.changeBaseLayer(this.mapConfig.defaultBaseLayer.id);
    },
    beforeUnmount() {
        this.$refs.zumap.leafletObject.off('zoomend', this.handleZoomEnd);
    },
    methods: {
        changeBaseLayer(layerId) {
            this.currentLayer = this.baseLayers.get(layerId);
        },
        toggleCategory(category) {
            const parent = this.tree.get(category);
            if (this.selectedCategories.has(category)) {
                this.selectedCategories.delete(category);
                if (parent) {
                    parent.children.forEach((child) => {
                        this.selectedCategories.delete(child.id);
                    });
                }
            } else {
                this.selectedCategories.add(category);
                if (parent) {
                    parent.children.forEach((child) => {
                        this.selectedCategories.add(child.id);
                    });
                }
            }
        },
        handleZoomEnd() {
            this.currentZoom = this.$refs.zumap.leafletObject.getZoom();
        },
        mapReady() {
            this.$refs.zumap.leafletObject.on('zoomend', this.handleZoomEnd);
            this.handleZoomEnd();
        },
    },
};
</script>

<style lang="scss" module>
.component :global {
    height: 100%;
    width: 100vw;

    .leaflet-container {
    background-color: black;
    }

    .label div{
        display:inline-block;
        transform:translate(-50%, -50%);
        color: #f8efae;
        text-shadow: #977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px;
        background-color: transparent;
        box-shadow: none;
        border: 0;
        font-size: 24px;
        text-align: center;
        line-height: .7;
    }
}
</style>
