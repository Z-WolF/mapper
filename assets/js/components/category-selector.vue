<template>
    <div class="row">
        <template
            v-for="node in categoryTree"
        >
            <category-button
                :id="node.id"
                :name="categories.get(node.id).name"
                :selected="0 === selected.size || selected.has(node.id)"
                :icon="categories.get(node.id).iconClass"
                :color="categories.get(node.id).color"
                @toggle-category="$emit('toggle-category', $event)"
            />
            <category-button
                v-for="child in node.children"
                :id="child.id"
                :key="child.id"
                :name="categories.get(child.id).name"
                :selected="0 === selected.size || selected.has(child.id)"
                :icon="categories.get(child.id).iconClass"
                :color="categories.get(child.id).color"
                @toggle-category="$emit('toggle-category', $event)"
            />
        </template>
    </div>
</template>

<script>
import CategoryButton from '@/components/category-button.vue';

export default {
    name: 'CategorySelector',
    components: {
        CategoryButton,
    },
    props: {
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
    computed: {
        categoryTree() {
            return Array.from(this.tree.values()).filter((node) => !this.categories.get(node.id).label);
        },
    },
};
</script>
