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
    const phi = 1.61803398875;
    const totalPoints = 3000;
    const radius = 5;

    // ângulo dourado derivado do phi
    const goldenAngle = 2 * Math.PI * (1 - 1 / phi);

    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(totalPoints * 3);
    const colors = new Float32Array(totalPoints * 3);

    const colorInside = new THREE.Color('#ff0000'); // vermelho
    const colorOutside = new THREE.Color('#00ffff'); // azul

    for (let i = 0; i < totalPoints; i++) {
        const angle = i * goldenAngle;
        const r = radius * Math.pow(i / totalPoints, 1 / phi); // crescimento baseado em φ

        const x = r * Math.cos(angle);
        const y = r * Math.sin(angle);
        const z = Math.sin(i * 0.03) * 0.2; // leve ondulação

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
        sizes.height = document.documentElement.clientHeight;
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
.spiral-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100dvh; /* usa viewport height estável em navegadores modernos */
    overflow: hidden;
}
.fixed {
    overflow: hidden;
}
</style>
