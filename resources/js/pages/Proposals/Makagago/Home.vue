<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import Swal from "sweetalert2";

const brand = {
  name: "Makagago",
  tagline: "Wazzup Man",
  facebook: "https://www.facebook.com/MakagagoWM09",
  youtube: "https://www.youtube.com/@MakagagoWazzupMan",
};

const nav = [
  { label: "Home", id: "top" },
  { label: "Featured", id: "featured" },
  { label: "Series", id: "series" },
  { label: "Merch", id: "merch" },
  { label: "Partners", id: "partners" },
  { label: "Partner With Us", id: "partner-with-us" },
];

const isLoading = ref(true);
const loadingFadeOut = ref(false);

const finishLoading = async () => {
  // start fade out animation
  loadingFadeOut.value = true;

  // wait for fade-out transition
  setTimeout(() => {
    isLoading.value = false;

    // allow your IntersectionObserver to work correctly after splash is gone
    nextTick(() => {
      window.dispatchEvent(new Event("scroll"));
    });
  }, 450);
};

const activeSection = ref("top");

let onScrollHandler = null;

const showBackToTop = ref(false);

// lock observer during programmatic scroll
const isProgrammaticScroll = ref(false);
let scrollLockTimer = null;

const headerOffset = 96;

const scrollTo = (id) => {
  const el = document.getElementById(id);
  if (!el) return;

  isProgrammaticScroll.value = true;
  activeSection.value = id;

  // 🔹 If going to top, hide back-to-top immediately
  if (id === "top") {
    showBackToTop.value = false;
  }

  const y = el.getBoundingClientRect().top + window.scrollY - headerOffset;
  window.scrollTo({ top: y, behavior: "smooth" });
  history.replaceState(null, "", `#${id}`);

  if (scrollLockTimer) clearTimeout(scrollLockTimer);
  scrollLockTimer = setTimeout(() => {
    isProgrammaticScroll.value = false;

    // 🔹 Safety check after scroll finishes
    if (window.scrollY <= 40) {
      showBackToTop.value = false;
      activeSection.value = "top";
    }
  }, 750);
};

let observer;

onMounted(() => {

  // splash visible on first load (tweak duration)
  setTimeout(() => {
    finishLoading();
  }, 1000);

  const ids = nav.map((n) => n.id);
  const sections = ids.map((id) => document.getElementById(id)).filter(Boolean);

  const hash = window.location.hash?.replace("#", "");
  if (hash && ids.includes(hash)) activeSection.value = hash;

  observer = new IntersectionObserver(
    (entries) => {
      if (isProgrammaticScroll.value) return;

      const visible = entries
        .filter((e) => e.isIntersecting)
        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

      if (visible?.target?.id) activeSection.value = visible.target.id;
    },
    {
      root: null,
      threshold: [0.25, 0.4, 0.55, 0.7],
      rootMargin: `-${headerOffset}px 0px -60% 0px`,
    }
  );

  sections.forEach((sec) => observer.observe(sec));

   onScrollHandler = () => {
        if (isProgrammaticScroll.value) return;

        const y = window.scrollY;

        // Show button after scrolling down
        showBackToTop.value = y > 400;

        // Force "Home" active near top
        if (y <= 40) {
            activeSection.value = "top";
            history.replaceState(null, "", "#top");
        }
    };

  window.addEventListener("scroll", onScrollHandler, { passive: true });
});

onBeforeUnmount(() => {
  if (onScrollHandler) window.removeEventListener("scroll", onScrollHandler);
  if (observer) observer.disconnect();
  if (scrollLockTimer) clearTimeout(scrollLockTimer);
});

const featured = [
  { title: "NORMAN MANGUSIN SINAGOT NA NI MANNY PACQUIAO ", href: "https://www.youtube.com/watch?v=PCSYa7PT8gI", thumb: "/images/makagago/featured-video-2.avif", note: "" },
  { title: "NORMAN MANGUSIN HINAMON SI MANNY PACQUIAO", href: "https://www.youtube.com/watch?v=1gec39fotzw", thumb: "/images/makagago/featured-video-1.avif", note: "" },
  { title: "NORMAN MANGUSIN ITINANGGI ANG MOMMY NYA", href: "https://www.youtube.com/watch?v=NaNr3Nag2Ks", thumb: "/images/makagago/featured-video-3.avif", note: "" },
];

const seriesRows = [
  {
    title: "Episodes",
    items: [
      { name: "FRANCIS LEO MARCOS EPISODES", count: "62 videos", thumb: "/images/makagago/series/flm-series.jpg", href: "https://www.youtube.com/watch?v=vKSA-e41zTI&list=PLvhbEtwm1bb6zdhISxA2FQ2ayuoAU8Rw6" },
      { name: "THE RISE AND FALL EPISODES", count: "14 videos", thumb: "/images/makagago/series/rise-and-fall-series.jpg", href: "https://www.youtube.com/watch?v=lgS3Fntii-E&list=PLvhbEtwm1bb7_byrjfun8g4ppAT-P04kS" },
      { name: "Reality Quest", count: "6 videos", thumb: "/images/makagago/series/reality-quest-series.jpg", href: "https://www.youtube.com/watch?v=L-slt4YwoLA&list=PLvhbEtwm1bb5M3vePLzQ3Dut9Wus7rKNm" },
      { name: "NICO DAVID EPISODES", count: "4 videos", thumb: "/images/makagago/series/nico-david-series.jpg", href: "https://www.youtube.com/watch?v=OOM9cmy_TXk&list=PLvhbEtwm1bb55tXe6WFeL0yc9WHt2De1u" },
      { name: "ATO EPISODES", count: "5 videos", thumb: "/images/makagago/series/ato-series.jpg", href: "https://www.youtube.com/watch?v=n-BJ_2SzE94&list=PLvhbEtwm1bb6LCz-TOIpXjGliHfqowS7J" },
      { name: "WHAMOS CRUZ EPISODES", count: "5 videos", thumb: "/images/makagago/series/whamos-series.jpg", href: "https://www.youtube.com/watch?v=XLcBS4gzULk&list=PLvhbEtwm1bb7bbU9wUmdcnc_WesBW_T8A" },
      { name: "PIO BALBUENA EPISODES", count: "6 videos", thumb: "/images/makagago/series/pio-series.jpg", href: "https://www.youtube.com/watch?v=M6-gZFQapQ4&list=PLvhbEtwm1bb6zMqMk0Kr3ZKBhiY7QNhYo" },
      { name: "BUGOY NA KOYKOY EPISODES", count: "2 videos", thumb: "/images/makagago/series/bugoy-series.jpg", href: "https://www.youtube.com/watch?v=PcuKmWBTohE&list=PLvhbEtwm1bb7USrJRr1FG31MtmebBtKHt" },
    ],
  },
];

const merch = [
  {
    name: "Repent (Black)",
    desc: "Comfortable black t-shirt —  bold white print.",
    price: "PHP 750",
    image: "/images/makagago/merch/repent-black.jpg",
    cta: "Chat to our Facebook Page",
    href: "https://www.messenger.com/t/792285597310101",
  },
  {
    name: "The Biggest (White)",
    desc: "Comfortable white t-shirt —  bold black print.",
    price: "PHP 650",
    image: "/images/makagago/merch/the-biggest-white.jpg",
    cta: "Chat to our Facebook Page",
    href: "https://www.messenger.com/t/792285597310101",
  }
];

const scrollRow = (id, dir = 1) => {
  const el = document.getElementById(id);
  if (!el) return;
  const amount = Math.round(el.clientWidth * 0.9) * dir;
  el.scrollBy({ left: amount, behavior: "smooth" });
};

const partners = [
  { name: "Manny Pay", logo: "/images/makagago/partners/mannypay.png", link:"https://www.mannypay.ph/" },
];

const form = ref({
  name: "",
  brand: "",
  email: "",
  budget: "",
  message: "",
});

const errors = ref({
  name: "",
  brand: "",
  email: "",
  message: "",
});

const touched = ref({
  name: false,
  brand: false,
  email: false,
  message: false,
});

const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

const validateField = (key) => {
  const value = (form.value[key] || "").trim();

  // reset
  errors.value[key] = "";

  if (key === "name" && value.length < 2) errors.value.name = "Please enter your name.";
  if (key === "brand" && value.length < 2) errors.value.brand = "Please enter your brand/company.";
  if (key === "email" && !isValidEmail(value)) errors.value.email = "Please enter a valid email address.";
  if (key === "message" && value.length < 10) errors.value.message = "Please write a short message (10+ chars).";
};

const validateForm = () => {
  // mark all as touched
  Object.keys(touched.value).forEach((k) => (touched.value[k] = true));

  validateField("name");
  validateField("brand");
  validateField("email");
  validateField("message");

  return !errors.value.name && !errors.value.brand && !errors.value.email && !errors.value.message;
};

const submitting = ref(false);

const submitPartnerWithUs = async () => {
  if (!validateForm()) {
    await Swal.fire({
      icon: "error",
      title: "Please check the form",
      text: "Some fields are missing or invalid.",
      confirmButtonText: "Okay",
    });
    return;
  }

  submitting.value = true;

  // DEMO: simulate submit delay
  setTimeout(async () => {
    submitting.value = false;

    await Swal.fire({
      icon: "success",
      title: "Inquiry submitted!",
      text: "This is a demo only — no data was sent.",
      confirmButtonText: "Got it",
    });

    // reset form
    form.value = { name: "", brand: "", email: "", budget: "", message: "" };

    // reset touched + errors
    Object.keys(touched.value).forEach((k) => (touched.value[k] = false));
    Object.keys(errors.value).forEach((k) => (errors.value[k] = ""));
  }, 650);
};
</script>

<template>
  <div id="top" class="min-h-screen bg-neutral-950 text-neutral-100">
    <!-- LOADING SPLASH -->
    <transition name="splash">
        <div
        v-if="isLoading"
        class="fixed inset-0 z-[999] grid place-items-center bg-neutral-950"
        >
        <!-- subtle glow -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 left-1/2 h-96 w-[42rem] -translate-x-1/2 rounded-full bg-red-600/20 blur-3xl" />
            <div class="absolute bottom-[-10rem] right-[-10rem] h-[28rem] w-[28rem] rounded-full bg-white/5 blur-3xl" />
        </div>

        <div
            :class="[
            'relative flex flex-col items-center text-center transition-all duration-500',
            loadingFadeOut ? 'opacity-0 scale-[0.98]' : 'opacity-100 scale-100'
            ]"
        >
            <div class="grid h-24 w-24 place-items-center rounded-3xl bg-white/5 ring-1 ring-white/10">
            <img
                src="/images/makagago/mg-logo.png"
                alt="MG"
                class="h-16 w-16 object-contain"
            />
            </div>

            <p class="mt-6 text-sm uppercase tracking-[0.35em] text-neutral-400">
            Makagago - Wazzup Man
            </p>

            <h2 class="mt-3 text-2xl font-black tracking-tight md:text-3xl">
            <span class="text-red-500">1L</span>GFV
            </h2>

            <div class="mt-6 h-1 w-44 overflow-hidden rounded-full bg-white/10 ring-1 ring-white/10">
            <div class="h-full w-1/2 animate-loading-bar rounded-full bg-red-600"></div>
            </div>

            <button
            type="button"
            @click="finishLoading"
            class="mt-6 text-xs text-neutral-400 hover:text-white"
            >
            Skip intro
            </button>
        </div>
        </div>
    </transition>

    <!-- subtle controlled glow -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
      <div class="absolute -top-48 left-1/2 h-96 w-[48rem] -translate-x-1/2 rounded-full bg-red-600/15 blur-3xl" />
      <div class="absolute top-40 right-[-10rem] h-96 w-96 rounded-full bg-white/5 blur-3xl" />
      <div class="absolute bottom-[-14rem] left-[-10rem] h-[28rem] w-[28rem] rounded-full bg-red-500/10 blur-3xl" />
    </div>

    <!-- Proposal disclaimer bar (full width) -->
    <div class="relative border-b border-white/10 bg-black/30 backdrop-blur">
        <div class="text-center px-6 py-3 md:px-10">
            <p class="text-xs text-neutral-300">
            Concept website proposal — <span class="font-semibold text-white">not an official site</span>. Built for pitching and preview only.
            </p>
        </div>
    </div>

    <!-- Header (FULL WIDTH) -->
    <header class="sticky top-0 z-50 border-b border-white/10 bg-neutral-950/80 backdrop-blur">
      <div class="w-full px-6 py-4 md:px-10">
        <div class="flex items-center justify-between gap-4">
          <!-- Left -->
          <div class="flex min-w-0 items-center gap-3">
            <div class="grid h-11 w-11 flex-none place-items-center rounded-xl bg-white/5 ring-1 ring-white/10">
              <img src="/images/makagago/mg-logo.png" alt="MG Logo" class="h-8 w-8 object-contain" />
            </div>
            <div class="min-w-0 leading-tight">
              <h1 class="truncate text-base font-semibold">{{ brand.name }} — {{ brand.tagline }}</h1>
            </div>
          </div>

          <!-- Right nav (scrollable) -->
          <nav
            class="flex max-w-[62%] items-center gap-2 overflow-x-auto whitespace-nowrap
                   [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
          >
            <a
              v-for="n in nav"
              :key="n.id"
              href="#"
              @click.prevent="scrollTo(n.id)"
              :class="[
                'shrink-0 rounded-xl px-4 py-2 text-sm ring-1 transition',
                activeSection === n.id
                  ? 'bg-red-600 text-white ring-red-500/40'
                  : 'bg-white/5 text-neutral-200 ring-white/10 hover:bg-white/10'
              ]"
            >
              {{ n.label }}
            </a>
          </nav>
        </div>
      </div>
    </header>

    <!-- HERO  -->
    <section class="relative min-h-screen w-full px-6 py-14 md:px-10 md:py-20">
      <div class="grid gap-10 lg:grid-cols-12 lg:items-center">
        <div class="lg:col-span-6">

          <h2 class="mt-5 text-5xl font-black tracking-tight md:text-6xl">
            <span class="text-white">THE BIGGEST</span>
            <span class="block text-red-500">MAKAGAGO</span>
          </h2>

          <p class="mt-5 max-w-2xl text-neutral-300 leading-relaxed">
            No sugar-coating. Just real talk, viral callouts, and stories you won’t hear anywhere else. Wassup mga kahombre.
          </p>

          <div class="mt-8 flex flex-wrap gap-3">
            <a
              :href="brand.youtube"
              target="_blank"
              class="inline-flex items-center justify-center rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-500"
            >
              Watch on YouTube →
            </a>

            <a
              :href="brand.facebook"
              target="_blank"
              class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm ring-1 ring-white/10 hover:bg-blue-500"
            >
              Follow on Facebook
            </a>

            <a
              href="#"
              @click.prevent="scrollTo('partner-with-us')"
              class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-neutral-950 hover:bg-neutral-200"
            >
              Partner With Us
            </a>
          </div>
        </div>

        <div class="lg:col-span-6">
          <div class="relative overflow-hidden rounded-3xl bg-white/5 ring-1 ring-white/10">
            <div class="absolute inset-0 bg-gradient-to-b from-red-600/10 via-transparent to-black/30" />

            <div class="p-5 md:p-6">
              <div class="flex items-center gap-3">
                <div class="grid h-12 w-12 place-items-center rounded-2xl bg-black/40 ring-1 ring-white/10">
                  <img src="/images/makagago/mg-logo.png" alt="MG Logo" class="h-9 w-9 object-contain" />
                </div>
                <div>
                  <p class="text-sm font-semibold leading-tight">{{ brand.name }}</p>
                  <p class="text-xs text-neutral-300">{{ brand.tagline }}</p>
                </div>
              </div>

              <div class="mt-5 overflow-hidden rounded-2xl ring-1 ring-white/10">
                <img
                  src="/images/makagago/makagago-dp.jpg"
                  alt="Makagago profile picture"
                  class="h-[420px] w-full object-cover md:h-[520px]"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURED VIDEOS  -->
    <section id="featured" class="relative w-full px-6 py-14 md:px-10 md:py-20">
      <div class="flex items-end justify-between gap-4">
        <div>
          <h3 class="text-3xl font-extrabold tracking-tight md:text-4xl">Featured Videos</h3>
        </div>
        <a href="https://www.youtube.com/@MakagagoWazzupMan/videos" target="_blank" class="text-sm text-neutral-300 hover:text-white">
          See all videos →
        </a>
      </div>

      <div class="mt-10 grid gap-6 lg:grid-cols-3">
        <a
          v-for="(v, idx) in featured"
          :key="idx"
          :href="v.href"
          target="_blank"
          class="group rounded-3xl bg-white/5 p-6 ring-1 ring-white/10 hover:bg-white/10"
        >
          <div class="flex items-center justify-between gap-3">
            <p class="text-base font-semibold">{{ v.title }}</p>
            <span class="rounded-full bg-red-600/20 px-2 py-1 text-xs text-red-200 ring-1 ring-red-500/30">
              YouTube
            </span>
          </div>

          <div class="mt-5 h-48 rounded-2xl bg-black/40 ring-1 ring-white/10 grid place-items-center">
            <img :src="v.thumb" :alt="v.title" />
          </div>

          <p class="mt-4 text-sm text-neutral-300">{{ v.note }}</p>
        </a>
      </div>
    </section>

    <!-- MAKAGAGO SERIES -->
    <section id="series" class="relative w-full px-6 py-14 md:px-10 md:py-20">
      <div class="flex items-end justify-between gap-4">
        <div>
          <h3 class="text-3xl font-extrabold tracking-tight md:text-4xl">Makagago Series</h3>
        </div>
        <a :href="brand.youtube + '/playlists'" target="_blank" class="text-sm text-neutral-300 hover:text-white">
          View all playlists →
        </a>
      </div>

      <div class="mt-10 space-y-12">
        <div v-for="(row, rIndex) in seriesRows" :key="row.title">
          <div class="mb-4 flex items-center justify-between gap-4">
            <h4 class="text-xl font-bold text-white">{{ row.title }}</h4>

            <div class="hidden gap-2 md:flex">
              <button
                type="button"
                class="rounded-xl bg-white/5 px-3 py-2 text-sm ring-1 ring-white/10 hover:bg-white/10"
                @click="scrollRow(`row-${rIndex}`, -1)"
              >
                ←
              </button>
              <button
                type="button"
                class="rounded-xl bg-white/5 px-3 py-2 text-sm ring-1 ring-white/10 hover:bg-white/10"
                @click="scrollRow(`row-${rIndex}`, 1)"
              >
                →
              </button>
            </div>
          </div>

          <div class="relative">
            <div
              :id="`row-${rIndex}`"
              class="flex gap-5 overflow-x-auto pb-2 pr-2 snap-x snap-mandatory scroll-smooth
                     [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
            >
              <a
                v-for="item in row.items"
                :key="item.name"
                :href="item.href"
                target="_blank"
                class="group w-[240px] flex-none snap-start"
              >
                <div
                  class="relative overflow-hidden rounded-2xl bg-white/5 ring-1 ring-white/10
                         transition group-hover:-translate-y-1 group-hover:bg-white/10"
                >
                  <img :src="item.thumb" :alt="item.name" class="h-[140px] w-full object-cover" />
                  <div class="absolute bottom-3 right-3 rounded-lg bg-black/70 px-2 py-1 text-xs text-white ring-1 ring-white/10">
                    {{ item.count }}
                  </div>
                  <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 transition group-hover:opacity-100"></div>
                </div>

                <div class="mt-3">
                  <p class="text-sm font-semibold text-white line-clamp-2">{{ item.name }}</p>
                  <p class="mt-1 text-xs text-neutral-300">View full playlist →</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="merch" class="relative w-full px-6 py-14 md:px-10 md:py-20">
    <div class="text-center">
        <div class="mx-auto inline-flex items-center gap-3 rounded-full bg-white/5 px-5 py-2 text-sm text-neutral-200 ring-1 ring-white/10">
        The Biggest Official Merchandise
        </div>
        <p class="mt-3 text-sm text-neutral-300">
        Support the brand with official the biggest gear.
        </p>
    </div>

    <div class="mt-10 flex flex-wrap justify-center gap-6">
        <article
        v-for="m in merch"
        :key="m.name"
        class="w-full max-w-[420px] md:w-[420px]
                overflow-hidden rounded-3xl bg-white/5 ring-1 ring-white/10
                transition hover:bg-white/10"
        >
        <div class="relative">
            <img :src="m.image" :alt="m.name" class="h-56 w-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent" />
        </div>

        <div class="p-6">
            <h4 class="text-xl font-bold">{{ m.name }}</h4>
            <p class="mt-2 text-sm text-neutral-300 leading-relaxed">
            {{ m.desc }}
            </p>

            <div class="mt-4 flex items-center gap-2 text-sm font-semibold">
            <span class="inline-flex items-center gap-2 rounded-full bg-red-600/20 px-3 py-1 text-red-200 ring-1 ring-red-500/30">
                🏷️ {{ m.price }}
            </span>
            </div>

            <a
            :href="m.href"
            target="_blank"
            class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-500"
            >
            {{ m.cta }}
            </a>
        </div>
        </article>
    </div>
    </section>


    <!-- PARTNERS  -->
    <section id="partners" class="relative w-full px-6 py-14 md:px-10 md:py-20">
        <div class="text-center">
            <h3 class="text-3xl font-extrabold tracking-tight md:text-4xl">
            Partners
            </h3>
            <p class="mt-2 text-sm text-neutral-300">
            Brands and collaborators <br> (proposal demo — replace with official partner list).
            </p>
        </div>

        <!-- Centered, auto-adjusting logo list -->
        <div class="mt-10 flex flex-wrap justify-center gap-6">
            <div
            v-for="p in partners"
            :key="p.name"
            class="group flex h-24 w-[100px] items-center justify-center
                    rounded-2xl bg-white/5 ring-1 ring-white/10
                    transition hover:bg-white/10 cursor-pointer relative"
            >
            <a :href="p.link" target="_blank" class="absolute inset-0 z-10" />
            <img
                :src="p.logo"
                :alt="p.name"
                class=" object-contain opacity-70 
                    transition group-hover:opacity-100 group-hover:grayscale-0"
            />
            </div>
        </div>
    </section>

    <!-- PARTNER WITH US  -->
    <section id="partner-with-us" class="relative w-full px-6 py-14 md:px-10 md:py-20">
      <div class="rounded-3xl bg-white/5 p-7 ring-1 ring-white/10 md:p-10">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-start">
          <div class="lg:col-span-5">
            <h3 class="text-3xl font-extrabold tracking-tight md:text-4xl">Partner With Us</h3>
            <p class="mt-3 text-sm text-neutral-300">
              A clean collab funnel so brands can reach out quickly <br> sponsorships, guesting, partnerships, promos.
            </p>
            
          </div>

          <div class="lg:col-span-7">
            <form class="grid gap-4">
              <div class="grid gap-4 md:grid-cols-2">
                <div>
                  <label class="text-xs text-neutral-300">Your Name</label>
                    <input
                    v-model="form.name"
                    type="text"
                    @blur="touched.name = true; validateField('name')"
                    class="mt-2 w-full rounded-xl bg-black/40 px-4 py-3 text-sm ring-1 ring-white/10 placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-red-500/60"
                    placeholder="Juan Dela Cruz"
                    />
                    <p v-if="touched.name && errors.name" class="mt-2 text-xs text-red-300">
                    {{ errors.name }}
                    </p>
                </div>
                <div>
                  <label class="text-xs text-neutral-300">Brand / Company</label>
                    <input
                    v-model="form.brand"
                    type="text"
                    @blur="touched.brand = true; validateField('brand')"
                    class="mt-2 w-full rounded-xl bg-black/40 px-4 py-3 text-sm ring-1 ring-white/10 placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-red-500/60"
                    placeholder="Brand name"
                    />
                    <p v-if="touched.brand && errors.brand" class="mt-2 text-xs text-red-300">
                    {{ errors.brand }}
                    </p>
                </div>
              </div>

              <div class="grid gap-4 md:grid-cols-2">
                <div>
                  <label class="text-xs text-neutral-300">Email</label>
                    <input
                    v-model="form.email"
                    type="email"
                    @blur="touched.email = true; validateField('email')"
                    class="mt-2 w-full rounded-xl bg-black/40 px-4 py-3 text-sm ring-1 ring-white/10 placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-red-500/60"
                    placeholder="brand@email.com"
                    />
                    <p v-if="touched.email && errors.email" class="mt-2 text-xs text-red-300">
                    {{ errors.email }}
                    </p>
                </div>
              </div>

              <div>
                <label class="text-xs text-neutral-300">Message</label>
                <textarea
                v-model="form.message"
                rows="5"
                @blur="touched.message = true; validateField('message')"
                class="mt-2 w-full rounded-xl bg-black/40 px-4 py-3 text-sm ring-1 ring-white/10 placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-red-500/60"
                placeholder="Tell us about the campaign, timeline, and goals..."
                />
                <p v-if="touched.message && errors.message" class="mt-2 text-xs text-red-300">
                {{ errors.message }}
                </p>
              </div>

                <div class="flex flex-col gap-2">
                <button
                    type="button"
                    @click="submitPartnerWithUs"
                    :disabled="submitting"
                    class="rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-500 disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    {{ submitting ? "Submitting..." : "Submit Inquiry" }}
                </button>

                <p class="text-xs text-neutral-400">
                    Demo only — this form shows validation + success popup, but doesn’t send data yet.
                </p>
                </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="mt-12 border-t border-white/10 pt-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <div class="flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-xl bg-white/5 ring-1 ring-white/10">
              <img src="/images/makagago/mg-logo.png" alt="MG Logo" class="h-7 w-7 object-contain" />
            </div>
            <div class="text-sm">
              <p class="font-semibold">{{ brand.name }} — {{ brand.tagline }}</p>
            </div>
          </div>

          <div class="flex flex-wrap gap-3">
            <a :href="brand.facebook" target="_blank" class="text-sm text-neutral-300 hover:text-white">Facebook</a>
            <a :href="brand.youtube" target="_blank" class="text-sm text-neutral-300 hover:text-white">YouTube</a>
            <a href="#" @click.prevent="scrollTo('partner-with-us')" class="text-sm text-neutral-300 hover:text-white">
              Partner With Us
            </a>
          </div>
        </div>

        <p class="mt-6 text-xs text-neutral-500">
          © {{ new Date().getFullYear() }} • Built as a proposal concept on whadancel.dev
        </p>
      </footer>
    </section>
  </div>

    <!-- Back to Top Button -->
    <button
    v-if="showBackToTop"
    @click="scrollTo('top')"
    class="fixed bottom-6 right-6 z-50
            flex items-center justify-center
            h-12 w-12 rounded-full
            bg-red-600 text-white
            shadow-lg shadow-black/40
            ring-1 ring-red-500/40
            transition hover:bg-red-500
            focus:outline-none"
    aria-label="Back to top"
    >
    ↑
    </button>
</template>
<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.25s ease-out;
}

/* fade transition for the overlay */
.splash-enter-active,
.splash-leave-active {
  transition: opacity 0.35s ease;
}
.splash-enter-from,
.splash-leave-to {
  opacity: 0;
}

/* animated loading bar */
@keyframes loadingBar {
  0% { transform: translateX(-80%); }
  100% { transform: translateX(220%); }
}
.animate-loading-bar {
  animation: loadingBar 1s ease-in-out infinite;
}
</style>
