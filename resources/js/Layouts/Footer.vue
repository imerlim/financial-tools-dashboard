<script setup>
const props = defineProps({
    homeUrl: { type: String, required: true },
    loginUrl: { type: String, required: true },
});
</script>

<template>
    <footer class="bg-white dark:bg-slate-950 border-t border-black dark:border-white">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-6 lg:px-8">
            <div class="m-2 flex justify-center space-x-6 xl:col-span-6">
                <!-- Instagram -->
                <a
                    href="https://www.instagram.com/i.merlim/"
                    target="_blank"
                    class="text-slate-700 hover:text-pink-700 dark:text-white dark:hover:text-pink-700"
                >
                    <span class="sr-only">Instagram</span>
                    <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M7.75 2A5.75 5.75 0 002 7.75v8.5A5.75 5.75 0 007.75 22h8.5A5.75 5.75 0 0022 16.25v-8.5A5.75 5.75 0 0016.25 2h-8.5zm7.75 3.25a1 1 0 112 0 1 1 0 01-2 0zM12 7a5 5 0 110 10 5 5 0 010-10z"
                        />
                    </svg>
                </a>

                <a
                    href="https://www.linkedin.com/in/igor-merlim/"
                    target="_blank"
                    class="text-slate-700 hover:text-sky-700 dark:text-white dark:hover:text-sky-700"
                >
                    <span class="sr-only">LinkedIn</span>

                    <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 640 640" aria-hidden="true">
                        <!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path
                            d="M160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 160C544 124.7 515.3 96 480 96L160 96zM165 266.2L231.5 266.2L231.5 480L165 480L165 266.2zM236.7 198.5C236.7 219.8 219.5 237 198.2 237C176.9 237 159.7 219.8 159.7 198.5C159.7 177.2 176.9 160 198.2 160C219.5 160 236.7 177.2 236.7 198.5zM413.9 480L413.9 376C413.9 351.2 413.4 319.3 379.4 319.3C344.8 319.3 339.5 346.3 339.5 374.2L339.5 480L273.1 480L273.1 266.2L336.8 266.2L336.8 295.4L337.7 295.4C346.6 278.6 368.3 260.9 400.6 260.9C467.8 260.9 480.3 305.2 480.3 362.8L480.3 480L413.9 480z"
                        />
                    </svg>
                </a>

                <a
                    href="https://wa.me/5521982100397"
                    target="_blank"
                    class="text-slate-700 hover:text-green-700 dark:text-white dark:hover:text-green-700"
                >
                    <span class="sr-only">WhatsApp</span>
                    <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 640 640" aria-hidden="true">
                        <!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path
                            d="M188.1 318.6C188.1 343.5 195.1 367.8 208.3 388.7L211.4 393.7L198.1 442.3L248 429.2L252.8 432.1C273 444.1 296.2 450.5 319.9 450.5L320 450.5C392.6 450.5 453.3 391.4 453.3 318.7C453.3 283.5 438.1 250.4 413.2 225.5C388.2 200.5 355.2 186.8 320 186.8C247.3 186.8 188.2 245.9 188.1 318.6zM370.8 394C358.2 395.9 348.4 394.9 323.3 384.1C286.5 368.2 261.5 332.6 256.4 325.4C256 324.8 255.7 324.5 255.6 324.3C253.6 321.7 239.4 302.8 239.4 283.3C239.4 264.9 248.4 255.4 252.6 251C252.9 250.7 253.1 250.5 253.3 250.2C256.9 246.2 261.2 245.2 263.9 245.2C266.5 245.2 269.2 245.2 271.5 245.3L272.3 245.3C274.6 245.3 277.5 245.3 280.4 252.1C281.6 255 283.4 259.4 285.3 263.9C288.6 271.9 292 280.2 292.6 281.5C293.6 283.5 294.3 285.8 292.9 288.4C289.5 295.2 286 298.8 283.6 301.4C280.5 304.6 279.1 306.1 281.3 310C296.6 336.3 311.9 345.4 335.2 357.1C339.2 359.1 341.5 358.8 343.8 356.1C346.1 353.5 353.7 344.5 356.3 340.6C358.9 336.6 361.6 337.3 365.2 338.6C368.8 339.9 388.3 349.5 392.3 351.5C393.1 351.9 393.8 352.2 394.4 352.5C397.2 353.9 399.1 354.8 399.9 356.1C400.8 358 400.8 366 397.5 375.2C394.2 384.5 378.4 392.9 370.8 394zM544 160C544 124.7 515.3 96 480 96L160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 160zM244.1 457.9L160 480L182.5 397.8C168.6 373.8 161.3 346.5 161.3 318.5C161.4 231.1 232.5 160 319.9 160C362.3 160 402.1 176.5 432.1 206.5C462 236.5 480 276.3 480 318.7C480 406.1 407.3 477.2 319.9 477.2C293.3 477.2 267.2 470.5 244.1 457.9z"
                        />
                    </svg>
                </a>
            </div>

            <div class="sm:col-span-6 flex flex-col items-center">
                <div class="sm:ml-auto">
                    <p class="text-xl text-slate-900 dark:text-white text-center sm:text-left">Contact emails</p>
                    <p class="text-lg text-slate-900 dark:text-white text-center sm:text-left">imerlimdev@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>
</template>
