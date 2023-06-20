<template>
    <div :class="['col-3', 'g-0', 'text-center', $style.component]">
        <a
            href="#"
            :class="[selected ? 'toggledOn' : 'toggledOff']"
            @click="$emit('toggle-category', id)"
        >
            <span
                :class="['icon-background', 'circle', 'category-icon', icon]"
                :style="`background-color: ${color}; border-color: ${color}`"
            />
            <p class="label">{{ name }}</p>
        </a>
    </div>
</template>

<script>
export default {
    name: 'CategoryButton',
    props: {
        id: {
            type: Number,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        selected: {
            type: Boolean,
            required: true,
        },
        icon: {
            type: String,
            required: true,
        },
        color: {
            type: String,
            required: true,
        },
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
