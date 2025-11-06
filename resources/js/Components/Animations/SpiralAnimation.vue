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

    // --- CENA ---
    scene = new THREE.Scene();
    scene.background = new THREE.Color('#000011');

    // --- CÂMERA ---
    camera = new THREE.PerspectiveCamera(75, sizes.width / sizes.height, 0.1, 100);
    camera.position.z = 7;
    scene.add(camera);

    // --- RENDERIZADOR ---
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(sizes.width, sizes.height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    if (spiralContainer.value) {
        spiralContainer.value.appendChild(renderer.domElement);
    }

    // --- PARÂMETROS DA ESPIRAL ---
    const totalPoints = 8000;
    const turns = 69; // número de voltas
    const height = 169; // altura total
    const radius = 2; // raio máximo

    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(totalPoints * 3);
    const colors = new Float32Array(totalPoints * 3);

    const colorStart = new THREE.Color('#00ffff');
    const colorEnd = new THREE.Color('#ff00ff');

    // --- GERA PONTOS ---
    for (let i = 0; i < totalPoints; i++) {
        const t = i / totalPoints;
        const angle = t * turns * Math.PI * 2; // ângulo da volta
        const r = radius * t; // aumenta o raio progressivamente
        const x = Math.cos(angle) * r;
        const y = (t - 0.5) * height; // distribui verticalmente
        const z = Math.sin(angle) * r;

        const i3 = i * 3;
        positions[i3] = x;
        positions[i3 + 1] = y;
        positions[i3 + 2] = z;

        const color = colorStart.clone().lerp(colorEnd, t);
        colors[i3] = color.r;
        colors[i3 + 1] = color.g;
        colors[i3 + 2] = color.b;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 4));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 4));

    // --- MATERIAL ---
    const material = new THREE.PointsMaterial({
        size: 0.05,
        sizeAttenuation: true,
        vertexColors: true,
        blending: THREE.AdditiveBlending,
        transparent: true,
        opacity: 0.9,
    });

    // --- OBJETO ---
    spiral = new THREE.Points(geometry, material);
    scene.add(spiral);

    // --- ANIMAÇÃO ---
    const clock = new THREE.Clock();

    const animate = () => {
        const elapsed = clock.getElapsedTime();

        spiral.rotation.y = elapsed * 0.5;
        spiral.rotation.x = Math.sin(elapsed * 0.3) * 0.3;

        // vibração suave dos pontos
        const positions = geometry.attributes.position.array;
        for (let i = 0; i < positions.length; i += 1) {
            positions[i + 2] += Math.sin(elapsed * 2 + i * 0.002) * 0.0005;
        }
        geometry.attributes.position.needsUpdate = true;

        renderer.render(scene, camera);
        animationFrame = requestAnimationFrame(animate);
    };

    animate();

    // --- REDIMENSIONAMENTO ---
    const handleResize = () => {
        sizes.width = window.innerWidth;
        sizes.height = window.innerHeight;
        camera.aspect = sizes.width / sizes.height;
        camera.updateProjectionMatrix();
        renderer.setSize(sizes.width, sizes.height);
    };

    window.addEventListener('resize', handleResize);

    // --- LIMPEZA ---
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
