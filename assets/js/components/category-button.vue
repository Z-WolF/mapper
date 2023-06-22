<template>
    <div :class="['col-3', 'g-0', 'text-center', $style.component]">
        <a
            href="#"
            :class="[selected ? 'toggledOn' : 'toggledOff']"
            @click="toggleCategory(id)"
        >
            <span
                :class="['icon-background', 'circle', 'category-icon', categories.get(id).iconClass]"
                :style="`background-color: ${color}; border-color: ${color}`"
            />
            <p class="label">{{ categories.get(id).name }}</p>
        </a>
    </div>
</template>

<script>
import { mapActions, mapState } from 'pinia';
import { useMapStore } from '@/Store/MapStore';

export default {
    name: 'CategoryButton',
    props: {
        id: {
            type: Number,
            required: true,
        },
    },
    computed: {
        selected() {
            return this.selectedCategories.size === 0 || this.selectedCategories.has(this.id);
        },
        color() {
            return this.categories.get(this.id).color;
        },
        ...mapState(useMapStore, ['categories', 'selectedCategories']),
    },
    methods: {
        ...mapActions(useMapStore, ['toggleCategory']),
    },
};
</script>

<style lang="scss" module>
.component :global {
    a {
        text-decoration: none;
    }

    .category-icon {
        position: relative;
        font-size: 36px;
    }

    .toggledOn {
        opacity: 1;
    }

    .toggledOff {
        opacity: 0.35;
    }

    .icon-background {
        color: white;
    }

    .label {
        text-align: center;
        font-size: 12px;
        margin-top: 10px;
        line-height: 100%;
        opacity: inherit;
        color: rgba(255,255,255,.8);
        font-weight: inherit;
        white-space: inherit;
        display: block;
    }
}
</style>
