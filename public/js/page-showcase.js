(() => {
  const showcase = document.querySelector('[data-page-showcase]');
  if (!showcase) return;

  const slides = [...showcase.querySelectorAll('[data-showcase-slide]')];
  const dots = [...showcase.querySelectorAll('[data-showcase-dot]')];
  const nextButton = showcase.querySelector('[data-showcase-next]');
  const timerLabel = showcase.querySelector('[data-showcase-timer]');
  const soundButton = showcase.querySelector('[data-showcase-sound]');
  const expandButton = showcase.querySelector('[data-showcase-expand]');
  const modal = showcase.querySelector('[data-showcase-modal]');
  const modalVideo = showcase.querySelector('[data-showcase-expanded-video]');
  const closeModalButton = showcase.querySelector('[data-showcase-close]');
  const autoAdvance = showcase.dataset.autoAdvance === 'true'
    && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const delay = 20000;
  let activeIndex = 0;
  let timer = null;
  let countdown = null;
  let touchStartX = null;
  let touchStartY = null;
  let soundEnabled = false;

  const youtubeCommand = (frame, func, args = []) => {
    if (!frame?.contentWindow) return;
    frame.contentWindow.postMessage(JSON.stringify({ event: 'command', func, args }), 'https://www.youtube-nocookie.com');
  };

  const setSoundButton = (enabled) => {
    soundEnabled = enabled;
    if (!soundButton) return;
    soundButton.setAttribute('aria-pressed', String(enabled));
    soundButton.setAttribute('aria-label', enabled ? 'Matikan suara video' : 'Aktifkan suara video');
    const label = soundButton.querySelector('span');
    if (label) label.textContent = enabled ? 'Suara aktif' : 'Suara';
  };

  const toggleSound = () => {
    const media = slides[1]?.querySelector('[data-showcase-video]');
    if (!media) return;
    const enabled = !soundEnabled;
    if (media.tagName === 'IFRAME') {
      youtubeCommand(media, enabled ? 'unMute' : 'mute');
      if (enabled) youtubeCommand(media, 'setVolume', [100]);
    } else {
      media.muted = !enabled;
    }
    setSoundButton(enabled);
  };

  const closeExpandedVideo = () => {
    if (modal?.open) modal.close();
    if (modalVideo?.hasAttribute('src')) modalVideo.removeAttribute('src');
    const baseVideo = slides[1]?.querySelector('iframe[data-showcase-video]');
    youtubeCommand(baseVideo, 'mute');
    setSoundButton(false);
  };

  const stopVideo = (slide) => {
    const video = slide.querySelector('video[data-showcase-video]');
    if (video) {
      video.pause();
      video.controls = false;
    }

    const frame = slide.querySelector('iframe[data-showcase-video]');
    if (frame && frame.hasAttribute('src')) frame.removeAttribute('src');
  };

  const startVideo = (slide) => {
    const media = slide.querySelector('[data-showcase-video]');
    if (!media || !media.dataset.src) return;

    if (media.tagName === 'IFRAME') {
      if (!media.hasAttribute('src')) media.src = media.dataset.src;
      return;
    }

    if (!media.hasAttribute('src')) {
      media.src = media.dataset.src;
      media.load();
    }
    media.muted = true;
    media.play().catch(() => {});
  };

  const clearTimers = () => {
    window.clearTimeout(timer);
    window.clearInterval(countdown);
    timer = null;
    countdown = null;
  };

  const setSlide = (requestedIndex, fromUser = false) => {
    const nextIndex = (requestedIndex + slides.length) % slides.length;
    clearTimers();

    if (nextIndex !== 1) closeExpandedVideo();

    slides.forEach((slide, index) => {
      const active = index === nextIndex;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', String(!active));
      if (active) slide.removeAttribute('inert');
      else {
        slide.setAttribute('inert', '');
        stopVideo(slide);
      }
    });

    dots.forEach((dot, index) => {
      const active = index === nextIndex;
      dot.classList.toggle('is-active', active);
      dot.setAttribute('aria-current', String(active));
    });

    activeIndex = nextIndex;
    if (nextButton) {
      nextButton.setAttribute('aria-label', activeIndex === 0 ? 'Geser ke video' : 'Kembali ke gambar');
      const text = nextButton.querySelector('span');
      if (text) text.textContent = activeIndex === 0 ? 'Lihat video' : 'Kembali ke gambar';
    }

    if (activeIndex === 1) {
      startVideo(slides[activeIndex]);
      if (timerLabel) timerLabel.textContent = 'Geser kembali untuk melihat gambar';
      return;
    }

    if (!autoAdvance || fromUser && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      if (timerLabel) timerLabel.textContent = 'Geser untuk melihat video';
      return;
    }

    let secondsLeft = Math.round(delay / 1000);
    if (timerLabel) timerLabel.textContent = `Video otomatis dalam ${secondsLeft} detik`;
    countdown = window.setInterval(() => {
      secondsLeft -= 1;
      if (timerLabel) timerLabel.textContent = `Video otomatis dalam ${secondsLeft} detik`;
    }, 1000);
    timer = window.setTimeout(() => setSlide(1), delay);
  };

  soundButton?.addEventListener('click', toggleSound);
  expandButton?.addEventListener('click', () => {
    const media = slides[1]?.querySelector('[data-showcase-video]');
    if (!modal || !modalVideo?.dataset.src) {
      if (media?.tagName === 'VIDEO') {
        media.controls = true;
        media.requestFullscreen?.().catch(() => {});
      }
      return;
    }
    const baseVideo = slides[1]?.querySelector('iframe[data-showcase-video]');
    youtubeCommand(baseVideo, 'mute');
    setSoundButton(false);
    modalVideo.src = modalVideo.dataset.src;
    modal.showModal();
  });
  closeModalButton?.addEventListener('click', closeExpandedVideo);
  modal?.addEventListener('click', (event) => {
    if (event.target === modal) closeExpandedVideo();
  });
  modal?.addEventListener('close', () => {
    if (modalVideo?.hasAttribute('src')) modalVideo.removeAttribute('src');
    const baseVideo = slides[1]?.querySelector('iframe[data-showcase-video]');
    youtubeCommand(baseVideo, 'mute');
    setSoundButton(false);
  });
  document.addEventListener('fullscreenchange', () => {
    if (document.fullscreenElement) return;
    const video = slides[1]?.querySelector('video[data-showcase-video]');
    if (video) video.controls = false;
  });

  nextButton?.addEventListener('click', () => setSlide(activeIndex === 0 ? 1 : 0, true));
  dots.forEach((dot) => {
    dot.addEventListener('click', () => setSlide(Number(dot.dataset.showcaseDot), true));
  });

  showcase.addEventListener('touchstart', (event) => {
    const touch = event.changedTouches[0];
    touchStartX = touch.clientX;
    touchStartY = touch.clientY;
  }, { passive: true });

  showcase.addEventListener('touchend', (event) => {
    if (touchStartX === null || touchStartY === null) return;
    const touch = event.changedTouches[0];
    const deltaX = touch.clientX - touchStartX;
    const deltaY = touch.clientY - touchStartY;
    if (Math.abs(deltaX) > 45 && Math.abs(deltaX) > Math.abs(deltaY)) {
      setSlide(activeIndex === 0 ? 1 : 0, true);
    }
    touchStartX = null;
    touchStartY = null;
  }, { passive: true });

  showcase.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowRight') setSlide(activeIndex === 0 ? 1 : 0, true);
    if (event.key === 'ArrowLeft') setSlide(activeIndex === 0 ? 1 : 0, true);
  });

  setSlide(0);
})();
