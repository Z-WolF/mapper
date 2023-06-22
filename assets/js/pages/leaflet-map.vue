<template>
    <div :class="[$style.component]">
        <l-map
            ref="zumap"
            :zoom="mapConfig.defaultZoom"
            :center="mapConfig.defaultCenter"
            :crs="L.extend({}, L.CRS.Simple, {
                transformation: new L.transformation(mapConfig.scaleX ?? 1, mapConfig.offsetX ?? 0, mapConfig.scaleY ?? -1, mapConfig.offsetY ?? 0),
            })"
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
                        const zoom = currentZoom;
                        return category.label
                            ? L.marker(latlng, {
                                icon: L.divIcon({
                                    className: 'text-center text-nowrap w-auto label',
                                    html: '<div>' + feature.properties.name + '</div>',
                                })
                            })
                            : L.marker(latlng, {
                                title: feature.properties.name,
                                icon:L.divIcon({
                                    className: 'feature',
                                    html: `<div class=&quot;circle circleMap-${zoom < 6 ? 'small' : 'medium'}&quot; style=&quot;background-color: ${category.color}; border-color: ${category.color}&quot;><span class=&quot;align-middle ${category.iconClass ?? ''} icnText-${zoom < 6 ? 'small' : 'medium'}&quot;></span></div>`,
                                }),
                            });
                    }
                }"
            />

            <info-box />

            <l-control-zoom
                position="bottomright"
            />
        </l-map>
    </div>
</template>

<script>
import L from 'leaflet';
import {
    LMap, LControlZoom, LTileLayer, LGeoJson,
} from '@vue-leaflet/vue-leaflet';
import {mapActions, mapState, mapWritableState} from 'pinia';
import InfoBox from '@/components/info-box';
import { useMapStore } from '@/Store/MapStore';

export default {
    name: 'LeafletMap',
    components: {
        LGeoJson,
        LTileLayer,
        LMap,
        LControlZoom,
        InfoBox,
    },
    computed: {
        L() {
            return L;
        },
        ...mapState(useMapStore, [
            'categories',
            'currentLayer',
            'currentZoom',
            'mapConfig',
            'selectedCategories',
            'visibleMarkers',
        ]),
        ...mapWritableState(useMapStore, ['currentZoom']),
    },
    mounted() {
        this.changeBaseLayer();
    },
    beforeUnmount() {
        this.$refs.zumap.leafletObject.off('zoomend', this.handleZoomEnd);
    },
    methods: {
        handleZoomEnd() {
            this.currentZoom = this.$refs.zumap.leafletObject.getZoom();
        },
        mapReady() {
            this.$refs.zumap.leafletObject.on('zoomend', this.handleZoomEnd);
            this.handleZoomEnd();
        },
        ...mapActions(useMapStore, ['changeBaseLayer']),
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
        color: #f8efae;
        text-shadow: #977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px,#977f41 0 0 3px;
        box-shadow: none;
        font-size: 24px;
        text-align: center;
        line-height: .7;
        transform:translate(-50%, 0);
    }

    .feature div{
        //display:inline-block;
        transform:translate(0, -50%);
        //background-color: transparent;
        //border: 0;
    }

    .circle {
        width: 64px;
        height: 64px;
        border-radius: 64px;
        line-height: 64px;
        display: inline-block;
        text-align: center;
        vertical-align: middle;
        color: white;
    }

    .circleMap {
        width: 23px !important;
        height: 23px !important;
        line-height: 22px !important;
    }

    .icnText {
        font-size: 16px;
    }

    .circleMap-medium {
        width: 23px !important;
        height: 23px !important;
        line-height: 22px !important;
        box-shadow: rgba(0,0,0,.25) 0 2px 6px !important;
    }

    .icnText-medium {
        font-size: 15px;
    }

    .circleMap-small {
        width: 16px !important;
        height: 16px !important;
        line-height: 14px !important;
        box-shadow: rgba(0,0,0,.25) 0 2px 6px !important;
    }

    .icnText-small {
        font-size: 10px;
    }
}
</style>
