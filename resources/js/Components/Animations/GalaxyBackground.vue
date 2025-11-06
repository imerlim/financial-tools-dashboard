<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import * as THREE from 'three';

const galaxyContainer = ref(null);

let renderer, camera, scene; // precisamos acessar no unmounted

onMounted(() => {
    const sizes = {
        width: window.innerWidth,
        height: window.innerHeight,
    };

    // Cena
    scene = new THREE.Scene();

    // Câmera
    camera = new THREE.PerspectiveCamera(75, sizes.width / sizes.height, 0.1, 100);
    camera.position.z = 5;
    scene.add(camera);

    // Renderizador
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(sizes.width, sizes.height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    if (galaxyContainer.value) {
        galaxyContainer.value.appendChild(renderer.domElement);
    }

    // Parâmetros da galáxia
    const parameters = {
        count: 20000,
        size: 0.02,
        radius: 5,
        branches: 3,
        randomness: 0.5,
        insideColor: new THREE.Color('#ff6030'),
        outsideColor: new THREE.Color('#1b3984'),
    };

    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(parameters.count * 3);
    const colors = new Float32Array(parameters.count * 3);

    for (let i = 0; i < parameters.count; i++) {
        const i3 = i * 3;
        const radius = Math.random() * parameters.radius;
        const branchAngle = ((i % parameters.branches) / parameters.branches) * Math.PI * 2;
        const spinAngle = radius * 0.3;
        const finalAngle = branchAngle + spinAngle;

        const randomPower = 3;
        const randomX = Math.pow(Math.random(), randomPower) * (Math.random() < 0.5 ? 1 : -1) * parameters.randomness;
        const randomY = Math.pow(Math.random(), randomPower) * (Math.random() < 0.5 ? 1 : -1) * parameters.randomness;
        const randomZ = Math.pow(Math.random(), randomPower) * (Math.random() < 0.5 ? 1 : -1) * parameters.randomness;

        positions[i3] = Math.cos(finalAngle) * radius + randomX;
        positions[i3 + 1] = randomY;
        positions[i3 + 2] = Math.sin(finalAngle) * radius + randomZ;

        const mixedColor = parameters.insideColor.clone();
        mixedColor.lerp(parameters.outsideColor, radius / parameters.radius);

        colors[i3] = mixedColor.r;
        colors[i3 + 1] = mixedColor.g;
        colors[i3 + 2] = mixedColor.b;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const material = new THREE.PointsMaterial({
        size: parameters.size,
        sizeAttenuation: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
        vertexColors: true,
    });

    const galaxy = new THREE.Points(geometry, material);
    scene.add(galaxy);

    const clock = new THREE.Clock();

    const tick = () => {
        const elapsedTime = clock.getElapsedTime();
        galaxy.rotation.y = elapsedTime * 0.03;
        renderer.render(scene, camera);
        requestAnimationFrame(tick);
    };
    tick();

    // listener de resize
    const handleResize = () => {
        sizes.width = window.innerWidth;
        sizes.height = window.innerHeight;
        camera.aspect = sizes.width / sizes.height;
        camera.updateProjectionMatrix();
        renderer.setSize(sizes.width, sizes.height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    };

    window.addEventListener('resize', handleResize);

    // remove o listener no unmount
});
onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
    renderer.dispose();
    geometry.dispose();
    material.dispose();
});
</script>

<template>
    <div ref="galaxyContainer" class="fixed top-0 left-0 w-full h-full"></div>
</template>
