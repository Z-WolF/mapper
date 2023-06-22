import { defineStore } from 'pinia';
import { markRaw } from 'vue';
import { fetchCategories, fetchMapBaseLayers, fetchTree } from '@/services/map-services';

export const useMapStore = defineStore('map', {
    state: () => ({
        currentZoom: 8,
        currentLayer: {},
        selectedCategories: new Set(),
        mapConfig: markRaw(window.gameConfig),
        features: markRaw(window.features),
        baseLayers: markRaw(fetchMapBaseLayers()),
        categories: markRaw(fetchCategories()),
        tree: markRaw(fetchTree()),
    }),
    getters: {
        visibleMarkers: (state) => {
            const selectedCategoriesSize = state.selectedCategories.size;
            const currentZoom = state.currentZoom;
            const currentLayerId = state.currentLayer.id;
            return state.features.filter((marker) => {
                const category = state.categories.get(marker.properties.category);

                return marker.properties.baseLayer === currentLayerId
                    && (
                        (
                            selectedCategoriesSize > 0 && state.selectedCategories.has(category.id)
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
    actions: {
        changeBaseLayer(layerId) {
            this.currentLayer = this.baseLayers.get(layerId ?? this.mapConfig.defaultBaseLayer.id);
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
    },
});
