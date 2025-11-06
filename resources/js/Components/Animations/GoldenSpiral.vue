<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import * as THREE from 'three';

const spiralContainer = ref(null);

let scene, camera, renderer, spiral, animationFrame;

onMounted(() => {
    const sizes = {
        width: window.innerWidth,
        height: window.innerHeight,
    };

    // Cena
    scene = new THREE.Scene();
    scene.background = new THREE.Color('#000010');

    // Câmera
    camera = new THREE.PerspectiveCamera(75, sizes.width / sizes.height, 0.1, 100);
    camera.position.z = 8;
    scene.add(camera);

    // Renderizador
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(sizes.width, sizes.height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    if (spiralContainer.value) {
        spiralContainer.value.appendChild(renderer.domElement);
    }

    // ---------- LEI ÁUREA ----------
    const phi = 1.61803398875; // número de ouro
    const totalPoints = 3000;
    const radius = 5;

    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(totalPoints * 3);
    const colors = new Float32Array(totalPoints * 3);

    const colorInside = new THREE.Color('#ff0000'); // dourado
    const colorOutside = new THREE.Color('#00ffff'); // laranja forte

    // Gera pontos da espiral baseada na proporção áurea
    for (let i = 0; i < totalPoints; i++) {
        const angle = i * 137.5 * (Math.PI / 180); // ângulo dourado em radianos
        const r = Math.sqrt(i / totalPoints) * radius; // crescimento suave

        const x = r * Math.cos(angle);
        const y = r * Math.sin(angle);
        const z = Math.sin(i * 0.05) * 0.5; // leve variação no Z

        const i3 = i * 3;
        positions[i3] = x;
        positions[i3 + 1] = y;
        positions[i3 + 2] = z;

        const mixedColor = colorInside.clone();
        mixedColor.lerp(colorOutside, i / totalPoints);

        colors[i3] = mixedColor.r;
        colors[i3 + 1] = mixedColor.g;
        colors[i3 + 2] = mixedColor.b;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const material = new THREE.PointsMaterial({
        size: 0.04,
        sizeAttenuation: true,
        vertexColors: true,
        blending: THREE.AdditiveBlending,
        transparent: true,
    });

    spiral = new THREE.Points(geometry, material);
    scene.add(spiral);

    // ---------- ANIMAÇÃO ----------
    const clock = new THREE.Clock();

    const animate = () => {
        const elapsedTime = clock.getElapsedTime();

        spiral.rotation.z = elapsedTime * 0.2;
        spiral.rotation.x = Math.sin(elapsedTime * 0.3) * 0.3;
        spiral.rotation.y = Math.cos(elapsedTime * 0.2) * 0.3;

        renderer.render(scene, camera);
        animationFrame = requestAnimationFrame(animate);
    };

    animate();

    // ---------- RESPONSIVIDADE ----------
    const handleResize = () => {
        sizes.width = window.innerWidth;
        sizes.height = window.innerHeight;
        camera.aspect = sizes.width / sizes.height;
        camera.updateProjectionMatrix();
        renderer.setSize(sizes.width, sizes.height);
    };

    window.addEventListener('resize', handleResize);

    // ---------- LIMPEZA ----------
    onUnmounted(() => {
        cancelAnimationFrame(animationFrame);
        window.removeEventListener('resize', handleResize);
        renderer.dispose();
        geometry.dispose();
        material.dispose();
    });
});
</script>

<template>
    <div ref="spiralContainer" class="fixed top-0 left-0 w-full h-full"></div>
</template>

<style scoped>
.fixed {
    overflow: hidden;
}
</style>
