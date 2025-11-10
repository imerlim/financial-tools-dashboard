<template>
    <div class="cubeContainer">
        <div class="cubeOfCubes">
            <div v-for="i in 27" :key="i" class="cube" :style="getCubePosition(i)">
                <div v-for="f in 6" :key="f" class="cube__face"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger, MotionPathPlugin } from 'gsap/all';

gsap.registerPlugin(ScrollTrigger, MotionPathPlugin);

// Função para calcular as posições CSS custom properties
const getCubePosition = i => {
    const x = (i % 3) * -100 + 100;
    const y = Math.floor((i % 9) / 3) * -100 + 100;
    const z = Math.floor((i / 9) % 3) * -100 + 100;
    return {
        '--x': `${x}%`,
        '--y': `${y}%`,
        '--z': `${z}%`,
    };
};

// Gera rotação aleatória (mantendo distância de 0)
const randRot = () => gsap.utils.random(100, 360) * (Math.random() <= 0.5 ? -1 : 1);

onMounted(() => {
    const cubes = document.querySelectorAll('.cube');
    const cubeContainer = document.querySelector('.cubeContainer');

    gsap.set(cubeContainer, { opacity: 0.4 });

    // 1. Animação de Rotação Individual (mantida)
    cubes.forEach(cube => {
        gsap.to(cube, {
            scrollTrigger: {
                scrub: true,
            },
            rotation: randRot(),
            rotationY: randRot(),
            ease: 'power3.in',
        });
    });

    // 2. Animação de Movimento Circular (mantida, infinita)
    const r = 15;
    gsap.to('.cubeOfCubes', {
        motionPath: {
            path: `M ${-r}, 0
              a ${r},${r} 0 1,0 ${r * 2},0
              a ${r},${r} 0 1,0 -${r * 2},0z`,
        },
        duration: 10,
        repeat: -1,
        ease: 'none',
    });

    // 3. ✨ NOVA ANIMAÇÃO: FADE OUT COM SCROLLTRIGGER
    gsap.to(cubeContainer, {
        opacity: 0, // Anima a opacidade para 0 (desaparecer)
        scrollTrigger: {
            trigger: 'body', // O gatilho da animação é a rolagem do corpo
            start: '0', // Inicia quando o topo da página (body) atinge o topo da viewport
            end: '1400', // Termina 1000 pixels abaixo do ponto de início
            scrub: 0.1, // Suaviza a animação com a rolagem
        },
    });
});

onUnmounted(() => {
    ScrollTrigger.getAll().forEach(trigger => trigger.kill());
    gsap.killTweensOf('.cube, .cubeOfCubes, .cubeContainer'); // Adicionando .cubeContainer
});
</script>

<style scoped lang="scss">
$cube-edge: 3em;

body {
    margin: 0;
    font-size: 6.5vmin;
    background-color: #0e100f;
}

[class*='cube'] {
    position: absolute;
}

.cubeContainer {
    position: fixed;
    inset: 0;
    opacity: 0.4;
}

.cubeOfCubes {
    top: 50vh;
    left: 50vw;
    perspective: 25em;
    will-change: transform;
    position: absolute;
}

.cube {
    top: calc(50% - $cube-edge / 2);
    left: calc(50% - $cube-edge / 2);
    width: $cube-edge;
    height: $cube-edge;
    transform: translate(var(--x), var(--y)) rotateY(-90deg) translateX(var(--z)) rotateY(90deg);
    transform-style: preserve-3d;
    will-change: transform;

    &__face {
        width: $cube-edge;
        height: $cube-edge;
        box-shadow: inset 0 0 0 2px #fffce1;
        --i: 0;
        transform: rotate3d(var(--i), calc(1 - var(--i)), 0, calc(var(--m, 0) * 90deg)) translateZ(0.5 * $cube-edge);

        &:nth-child(n + 5) {
            --i: 1;
        }

        @for $f from 1 through 6 {
            &:nth-child(#{$f}) {
                --m: if($f < 4, $f, pow(-1, $f));
            }
        }
    }
}
</style>
