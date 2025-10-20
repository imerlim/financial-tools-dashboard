<template>
    <div class="bg-gray-900">
        <GlobalMsg></GlobalMsg>
        <!-- Header -->
        <header class="absolute inset-x-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 p-1.5">
                        <img class="h-8 w-auto" src="../../../storage/app/public/logo_aminia.jpg" alt="Logo" />
                    </a>
                </div>

                <!-- Botões -->
                <div class="lg:flex lg:flex-1 lg:justify-end relative">
                    <!-- BOTÃO COM DROPDOWN -->
                    <div class="relative" ref="dropdownContainer1">
                        <button @click="openDrop1 = !openDrop1" class="cursor-pointer rounded-md mx-2 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:text-yellow-400 focus:outline-none">Aminia</button>

                        <div class="relative pt-1">
                            <a href="https://www.youtube.com/@aminiaprod" target="_blank" class="cursor-pointer rounded-md mx-2 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:text-yellow-400 focus:outline-none"> Black Music </a>
                        </div>
                        <!-- DROPDOWN -->
                        <div v-show="openDrop1" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1 px-2 text-yellow-400">
                                <button @click="openModalDescricao = true" class="cursor-pointer rounded-md text-sm font-semibold text-gray-200 shadow-xs hover:text-yellow-400 focus:outline-none">O que é Aminia Produções</button>
                                <button @click="openModalContato = true" class="cursor-pointer rounded-md text-sm font-semibold text-gray-200 shadow-xs hover:text-yellow-400 focus:outline-none">Contato</button>
                            </div>
                        </div>
                    </div>

                    <ModalMedium v-model="openModalDescricao" title="A Casa da Black Music">
                        <div class="bg-white text-gray-900 dark:bg-gray-900 dark:text-white p-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-6" style="text-align: left">
                                <h1 class="text-xl font-bold text-center">O que é a Aminia Produções</h1>
                                <h3>
                                    <br /><br />
                                    A Aminia Produções é uma produtora audiovisual independente criada por Lucas Madeira (<a href="https://www.instagram.com/diretor_blackwood?igsh=MXRxdnoyZ3FsN2tyMQ==" target="_blank" class="text-yellow-400">@diretor_blackwood</a>), dedicada a celebrar e divulgar a Black Music no Brasil.<br /><br />

                                    Mais do que uma produtora, a Aminia é um movimento que une arte, som e imagem para valorizar a cultura Black em todas as suas expressões. Encontramos no YouTube o espaço perfeito para compartilhar essa visão — produzindo tributos audiovisuais a grandes nomes da Black Music mundial, como Stevie Wonder, Michael Jackson, Blackstreet, TLC, SWV, Marvin Gaye e muitos outros.<br /><br />

                                    Cada produção é pensada como uma homenagem autêntica e respeitosa, recriando com qualidade e emoção os sons e visuais que marcaram gerações.<br /><br />

                                    Nosso propósito é claro: preservar o legado da Black Music, inspirar novas gerações e consolidar o espaço dessa cultura no audiovisual brasileiro.
                                </h3>
                            </div>
                        </div>
                    </ModalMedium>

                    <ModalMedium v-model="openModalContato" title="Contato">
                        <div class="bg-white text-gray-900 dark:bg-gray-900 dark:text-white p-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <CustomInput v-model="nome" id="nome" name="nome" label="Nome"></CustomInput>
                            </div>

                            <div class="sm:col-span-4">
                                <CustomInput v-model="email" id="email" name="email" label="Email"></CustomInput>
                            </div>

                            <div class="sm:col-span-4">
                                <CustomInput v-model="assunto" id="assunto" name="assunto" label="Assunto"></CustomInput>
                            </div>

                            <div class="sm:col-span-4">
                                <CustomTextArea v-model="mensagem" id="mensagem" name="mensagem" label="Mensagem" placeholder="Digite sua mensagem aqui..." :rows="5" />
                            </div>

                            <div class="sm:col-span-6 justify-end flex self-end mt-6">
                                <button @click="enviaMensagem()" type="button" class="cursor-pointer rounded-md mb-2 bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-amber-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 inline-flex">Enviar mensagem</button>
                            </div>
                        </div>
                    </ModalMedium>
                </div>
            </nav>
        </header>

        <main>
            <!-- Hero section -->
            <div class="relative isolate overflow-hidden bg-gray-900 min-h-screen pt-32 sm:pt-0">
                <img src="../../../storage/app/public/capateste1.png" alt="" class="absolute inset-0 -z-10 size-full object-cover" />

                <div class="absolute inset-0 bg-black/50-z-10"></div>
                <!-- <div class="absolute inset-0 bg-black/50 -z-10"></div> -->

                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                    <div class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-288.75" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-1">
                        <div class="hidden sm:mb-8 sm:flex sm:justify-center"></div>
                        <div class="absolute inset-0 -z-10 overflow-hidden">
                            <div class="circle-container">
                                <div class="half-circle red animate-spin-slow"></div>
                                <div class="half-circle blue animate-spin-slow"></div>
                            </div>
                        </div>
                        <div class="text-center hidden md:block">
                            <h1 class="text-lg pt-3 font-semibold tracking-tight text-balance text-yellow-400 sm:text-6xl">Bem-vindo à Aminia Produções</h1>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                    <div class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-288.75" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
                </div>
            </div>

            <!-- NOVA CAMADA -->
            <div class="relative z-10 bg-gray-900 w-screen h-screen">
                <div class="absolute inset-0 bg-gray-900" aria-hidden="true"></div>

                <div class="relative z-20 flex justify-center items-center w-full h-full">
                    <div class="px-6 text-center">
                        <div class="neon-sign">
                            A CASA DA <br />
                            BLACK MUSIC <br />
                            BRASILEIRA
                        </div>
                    </div>
                </div>
            </div>

            <!-- NOVA CAMADA -->
            <!-- <div class="relative z-10 bg-gray-900 pt-32 pb-20 sm:pt-24 sm:pb-24">
        <div class="absolute inset-0 bg-gray-900" aria-hidden="true"></div>

        <div class="relative z-20 flex justify-center items-center">
          <div class="px-6">
            <div class="neon-sign">
              A CASA DA <br>
              BLACK MUSIC <br>
              BRASILEIRA
            </div>
          </div>
        </div>
      </div> -->

            <!-- Testimonial section -->
            <div class="relative z-10 bg-gray-900 pt-32 pb-20 sm:pt-56 sm:pb-24 xl:pb-0">
                <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
                    <div class="absolute top-[calc(50%-36rem)] left-[calc(50%-19rem)] transform-gpu blur-3xl">
                        <div class="aspect-1097/1023 w-274.25 bg-linear-to-r from-[#ff4694] to-[#776fff] opacity-25" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
                    </div>
                </div>
                <div class="mx-auto flex max-w-7xl flex-col items-center gap-x-8 gap-y-10 px-6 sm:gap-y-8 lg:px-8 xl:flex-row xl:items-stretch">
                    <div class="-mt-8 w-full max-w-2xl xl:-mb-8 xl:w-96 xl:flex-none">
                        <div class="relative aspect-2/1 h-full md:-mx-8 xl:mx-0 xl:aspect-auto">
                            <div class="relative w-full h-64 md:h-80 xl:h-auto">
                                <img class="w-full h-full rounded-2xl bg-gray-900 object-contain shadow-2xl" src="/storage/Ceo_Aminia.jpeg" alt="Diretor Blackwood" />
                            </div>
                        </div>
                    </div>
                    <div class="w-full max-w-2xl xl:max-w-none xl:flex-auto xl:px-16 xl:py-24">
                        <figure class="relative isolate pt-6 sm:pt-12">
                            <svg viewBox="0 0 162 128" fill="none" aria-hidden="true" class="absolute top-0 left-0 -z-10 h-32 stroke-white/20">
                                <path id="b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb" d="M65.5697 118.507L65.8918 118.89C68.9503 116.314 71.367 113.253 73.1386 109.71C74.9162 106.155 75.8027 102.28 75.8027 98.0919C75.8027 94.237 75.16 90.6155 73.8708 87.2314C72.5851 83.8565 70.8137 80.9533 68.553 78.5292C66.4529 76.1079 63.9476 74.2482 61.0407 72.9536C58.2795 71.4949 55.276 70.767 52.0386 70.767C48.9935 70.767 46.4686 71.1668 44.4872 71.9924L44.4799 71.9955L44.4726 71.9988C42.7101 72.7999 41.1035 73.6831 39.6544 74.6492C38.2407 75.5916 36.8279 76.455 35.4159 77.2394L35.4047 77.2457L35.3938 77.2525C34.2318 77.9787 32.6713 78.3634 30.6736 78.3634C29.0405 78.3634 27.5131 77.2868 26.1274 74.8257C24.7483 72.2185 24.0519 69.2166 24.0519 65.8071C24.0519 60.0311 25.3782 54.4081 28.0373 48.9335C30.703 43.4454 34.3114 38.345 38.8667 33.6325C43.5812 28.761 49.0045 24.5159 55.1389 20.8979C60.1667 18.0071 65.4966 15.6179 71.1291 13.7305C73.8626 12.8145 75.8027 10.2968 75.8027 7.38572C75.8027 3.6497 72.6341 0.62247 68.8814 1.1527C61.1635 2.2432 53.7398 4.41426 46.6119 7.66522C37.5369 11.6459 29.5729 17.0612 22.7236 23.9105C16.0322 30.6019 10.618 38.4859 6.47981 47.558L6.47976 47.558L6.47682 47.5647C2.4901 56.6544 0.5 66.6148 0.5 77.4391C0.5 84.2996 1.61702 90.7679 3.85425 96.8404L3.8558 96.8445C6.08991 102.749 9.12394 108.02 12.959 112.654L12.959 112.654L12.9646 112.661C16.8027 117.138 21.2829 120.739 26.4034 123.459L26.4033 123.459L26.4144 123.465C31.5505 126.033 37.0873 127.316 43.0178 127.316C47.5035 127.316 51.6783 126.595 55.5376 125.148L55.5376 125.148L55.5477 125.144C59.5516 123.542 63.0052 121.456 65.9019 118.881L65.5697 118.507Z" />
                                <use href="#b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb" x="86" />
                            </svg>
                            <blockquote class="text-xl/8 font-semibold text-white sm:text-2xl/9">
                                <div class="w-full aspect-video">
                                    <iframe class="w-full h-full rounded-lg" src="https://www.youtube.com/embed/cbpA6JpI9ZI?autoplay=1&loop=1&playlist=cbpA6JpI9ZI&rel=0&controls=1&mute=1" title="YouTube video player" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen> </iframe>
                                </div>
                            </blockquote>
                            <figcaption class="mt-8 text-base">
                                <div class="font-semibold text-white">Lucas Madeira</div>
                                <div class="mt-1 text-gray-400">Diretor Blackwood</div>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-32 bg-white-900 sm:mt-56 border-t border-white">
            <div class="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8 lg:py-32">
                <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                    <div class="m-2 flex justify-center space-x-6 xl:col-span-3">
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/aminiaprod/" target="_blank" class="text-gray-400 hover:text-yellow-400">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M7.75 2A5.75 5.75 0 002 7.75v8.5A5.75 5.75 0 007.75 22h8.5A5.75 5.75 0 0022 16.25v-8.5A5.75 5.75 0 0016.25 2h-8.5zm7.75 3.25a1 1 0 112 0 1 1 0 01-2 0zM12 7a5 5 0 110 10 5 5 0 010-10z" />
                            </svg>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/AminiaProd" target="_blank" class="text-gray-400 hover:text-yellow-400">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 12a10 10 0 10-11.5 9.9v-7h-2v-2.9h2V9.5c0-2 1.2-3.2 3-3.2.9 0 1.8.2 1.8.2v2h-1c-1 0-1.3.6-1.3 1.2v1.4h2.3L15.5 15h-2v7A10 10 0 0022 12z" />
                            </svg>
                        </a>

                        <!-- YouTube -->
                        <a href="https://www.youtube.com/@aminiaprod" target="_blank" class="text-gray-400 hover:text-yellow-400">
                            <span class="sr-only">YouTube</span>
                            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 576 512" aria-hidden="true">
                                <path d="M549.7 124.1c-6.3-23.7-24.9-42.3-48.5-48.7C458.4 64 288 64 288 64s-170.4 0-213.2 11.4c-23.6 6.4-42.2 25-48.5 48.7C16 167.7 16 256 16 256s0 88.3 10.3 131.9c6.3 23.7 24.9 42.3 48.5 48.7C117.6 448 288 448 288 448s170.4 0 213.2-11.4c23.6-6.4 42.2-25 48.5-48.7C560 344.3 560 256 560 256s0-88.3-10.3-131.9zM232 336V176l142 80-142 80z" />
                            </svg>
                        </a>

                        <!-- TikTok -->
                        <a href="https://www.tiktok.com/@aminiaprod" target="_blank" class="text-gray-400 hover:text-yellow-400">
                            <span class="sr-only">TikTok</span>
                            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 448 512" aria-hidden="true">
                                <path d="M448 209.91v102.52c-19.69 9.14-41.29 15.27-64 17.71v-61.65c-18.39-2.66-35.88-8.98-51.59-18.48v95.74c0 88.37-71.63 160-160 160S12 434.12 12 345.74s71.63-160 160-160c5.55 0 11.01.28 16.4.83v71.52c-5.34-1.36-10.9-2.08-16.4-2.08-48.6 0-88 39.4-88 88s39.4 88 88 88c48.6 0 88-39.4 88-88V0h76.8c7.15 67.2 56.51 120.83 123.2 128v81.91z" />
                            </svg>
                        </a>

                        <!-- X (Twitter) -->
                        <a href="https://x.com/AminiaProd" target="_blank" class="text-gray-400 hover:text-yellow-400">
                            <span class="sr-only">X</span>
                            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true">
                                <path d="M389.2 48H464l-160 184 188 232H356.5L252 322.3 134.6 464H48l170-196.3L32 48h127.5l95.4 126.6L389.2 48z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes spin-slow {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.animate-spin-slow {
    animation: spin-slow 60s linear infinite;
}

.circle-container {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100vmin;
    /* Pode ajustar este valor se quiser um círculo maior ou menor */
    height: 100vmin;
    transform: translate(-50%, -50%);
}

.half-circle {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
}

.red {
    /* Usaremos o box-shadow para criar a "borda" e o brilho */
    box-shadow:
    /* 0 0 20px 5px rgba(255, 0, 0, 0.9); */ 0 0 5px 2px rgba(255, 0, 0, 0.9), 0 0 10px 3px rgba(255, 0, 0, 0.7), 0 0 15px 4px rgba(255, 0, 0, 0.4), 0 0 20px 5px rgba(255, 0, 0, 0.2);
    clip-path: polygon(0% 0%, 100% 0%, 100% 50%, 0% 50%);
}

.blue {
    /* Amarelo no exemplo */
    box-shadow: 0 0 5px 2px rgba(255, 230, 0, 0.9), 0 0 10px 3px rgba(255, 230, 0, 0.7), 0 0 15px 4px rgba(255, 230, 0, 0.4), 0 0 20px 4px rgba(255, 230, 0, 0.2);
    clip-path: polygon(0% 50%, 100% 50%, 100% 100%, 0% 100%);
}

body {
    background: #000; /* fundo preto */
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

/* LETREIRO NEON */

.neon-sign {
    border: 3px solid;
    border-radius: 15px;
    padding: 2rem 3rem;
    font-size: 2.5rem;
    font-weight: 800;
    text-transform: uppercase;
    text-align: center;

    /* Gradiente circular arco-íris */
    background: conic-gradient(from 0deg, #ff0040, #ff00ff, #8000ff, #00ffff, #00ff00, #ffff00, #ff0040);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;

    /* Glow neon */
    text-shadow: 0 0 10px rgba(255, 0, 64, 0.8), 0 0 20px rgba(255, 0, 255, 0.6);
    box-shadow: 0 0 20px rgba(255, 0, 64, 0.5), 0 0 40px rgba(255, 0, 255, 0.4), 0 0 60px rgba(128, 0, 255, 0.3);

    /* Faz girar */
    animation: spin-colors 8s linear infinite;
}

@keyframes spin-colors {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* letreiro neon versão 2 */

.neon-sign {
    border: 3px solid;
    border-radius: 15px;
    padding: 2rem 3rem;
    font-size: 2.5rem;
    font-weight: 800;
    text-transform: uppercase;
    text-align: center;

    /* Gradiente arco-íris */
    background: linear-gradient(90deg, #ff0040, #ff00ff, #8000ff, #00ffff, #00ff00, #ffff00, #ff0040);
    background-size: 400% 400%;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;

    /* Glow neon */
    text-shadow: 0 0 10px rgba(255, 0, 64, 0.8), 0 0 20px rgba(255, 0, 255, 0.6);
    box-shadow: 0 0 20px rgba(255, 0, 64, 0.5), 0 0 40px rgba(255, 0, 255, 0.4), 0 0 60px rgba(128, 0, 255, 0.3);

    /* Nova animação RGB */
    animation: rgb-spin 10s linear infinite;
}

@keyframes rgb-spin {
    0% {
        background-position: 0% 50%;
    }
    100% {
        background-position: 400% 50%;
    }
}
</style>

<script>
export default {
    data() {
        return {
            openDrop1: false,
            openModalContato: false,
            openModalDescricao: false,

            nome: null,
            email: null,
            assunto: null,
            mensagem: null,
        };
    },

    computed: {},

    watch: {},

    mounted() {
        let self = this;
        document.addEventListener('click', this.handleClickOutside);
    },

    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },

    methods: {
        handleClickOutside(event) {
            let self = this;

            const dropdown1 = self.$refs.dropdownContainer1;
            if (dropdown1 && !dropdown1.contains(event.target)) {
                self.openDrop1 = false;
            }
        },

        enviaMensagem() {
            let self = this;

            self.openModalContato = false;
            axios
                .post('envia-mensagem', {
                    nome: self.nome,
                    email: self.email,
                    assunto: self.assunto,
                    mensagem: self.mensagem,
                })
                .then(response => {
                    this.$msg.success('Mensagem enviada com sucesso!');
                });
        },
    },
};
</script>
