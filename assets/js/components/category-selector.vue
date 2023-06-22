<template>
    <div class="row">
        <template
            v-for="node in categoryTree"
        >
            <category-button
                :id="node.id"
            />
            <category-button
                v-for="child in node.children"
                :id="child.id"
                :key="child.id"
            />
        </template>
    </div>
</template>

<script>
import { mapState } from 'pinia';
import { useMapStore } from '@/Store/MapStore';
import CategoryButton from '@/components/category-button.vue';

export default {
    name: 'CategorySelector',
    components: {
        CategoryButton,
    },
    computed: {
        categoryTree() {
            return Array.from(this.tree.values()).filter((node) => !this.categories.get(node.id).label);
        },
        ...mapState(useMapStore, ['categories', 'tree']),
    },
};
</script>
