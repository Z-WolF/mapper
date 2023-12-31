function arrayIntoMap(arr) {
    const map = new Map();
    arr.forEach((element) => {
        map.set(element.id, element);
    });
    return map;
}

export function fetchMapBaseLayers() {
    return arrayIntoMap(window.baseLayers);
}

export function fetchCategories() {
    return arrayIntoMap(window.categories);
}

export function fetchTree() {
    return arrayIntoMap(window.categoryTree);
}
