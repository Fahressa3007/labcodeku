/* ============================================================
   labcodeku — Shared scripts
   Copy button, progress tracking, quiz handler, sidebar, etc.
   ============================================================ */

/* ====== UTIL: Copy kode ke clipboard ====== */
function copyCode(btn) {
  const wrapper = btn.closest('.code-wrapper');
  const code = wrapper.querySelector('pre code').innerText;
  navigator.clipboard.writeText(code).then(() => {
    const original = btn.textContent;
    btn.textContent = '✓ Disalin';
    btn.classList.add('copied');
    setTimeout(() => {
      btn.textContent = original;
      btn.classList.remove('copied');
    }, 1600);
  }).catch(() => {
    btn.textContent = '✗ Gagal';
    setTimeout(() => btn.textContent = 'Copy', 1600);
  });
}

/* ====== UTIL: Tab switching ====== */
function switchTab(e, targetId) {
  const container = e.currentTarget.closest('.tab-group') || document;
  container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  container.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  e.currentTarget.classList.add('active');
  document.getElementById(targetId).classList.add('active');
}

/* ====== PROGRESS TRACKING (localStorage) ====== */
const PROGRESS_KEY = 'labcodeku_progress';

function getProgress() {
  try {
    return JSON.parse(localStorage.getItem(PROGRESS_KEY) || '{}');
  } catch (e) {
    return {};
  }
}

function saveProgress(data) {
  try {
    localStorage.setItem(PROGRESS_KEY, JSON.stringify(data));
  } catch (e) { /* localStorage tidak tersedia */ }
}

function markLesson(topic, lessonId, btn) {
  const progress = getProgress();
  if (!progress[topic]) progress[topic] = {};

  const isDone = progress[topic][lessonId];

  if (isDone) {
    delete progress[topic][lessonId];
    if (btn) {
      btn.classList.remove('done');
      btn.innerHTML = '○ Tandai sudah dipelajari';
    }
  } else {
    progress[topic][lessonId] = Date.now();
    if (btn) {
      btn.classList.add('done');
      btn.innerHTML = '✓ Sudah dipelajari';
    }
  }

  saveProgress(progress);
  updateSidebarProgress(topic);
  updateHomeProgress();
}

function initMarkButtons() {
  const progress = getProgress();
  document.querySelectorAll('.mark-complete').forEach(btn => {
    const topic = btn.dataset.topic;
    const lessonId = btn.dataset.lesson;
    if (progress[topic] && progress[topic][lessonId]) {
      btn.classList.add('done');
      btn.innerHTML = '✓ Sudah dipelajari';
    } else {
      btn.innerHTML = '○ Tandai sudah dipelajari';
    }
    btn.addEventListener('click', () => markLesson(topic, lessonId, btn));
  });
}

function updateSidebarProgress(topic) {
  const progress = getProgress();
  const topicProgress = progress[topic] || {};

  document.querySelectorAll('.sidebar a[data-lesson]').forEach(link => {
    const lessonId = link.dataset.lesson;
    if (topicProgress[lessonId]) {
      link.classList.add('completed');
    } else {
      link.classList.remove('completed');
    }
  });
}

function updateHomeProgress() {
  // Cek apakah halaman ini homepage (ada class .home-progress)
  const total = {
    html: 12,
    css: 12,
    javascript: 14,
    php: 10
  };
  const progress = getProgress();

  Object.keys(total).forEach(topic => {
    const count = progress[topic] ? Object.keys(progress[topic]).length : 0;
    const percent = Math.min(100, Math.round((count / total[topic]) * 100));

    const bar = document.querySelector(`.progress-bar[data-topic="${topic}"] .progress-fill`);
    const text = document.querySelector(`.progress-bar[data-topic="${topic}"] .progress-text`);
    if (bar) bar.style.width = percent + '%';
    if (text) text.textContent = `${count}/${total[topic]} selesai`;
  });
}

/* ====== QUIZ HANDLER ====== */
function checkQuiz(btn, isCorrect, explanation) {
  const quiz = btn.closest('.quiz');
  const allOptions = quiz.querySelectorAll('.quiz-option');
  const feedback = quiz.querySelector('.quiz-feedback');

  // Disable semua tombol
  allOptions.forEach(opt => {
    opt.disabled = true;
    if (opt.dataset.correct === 'true') {
      opt.classList.add('correct');
    }
  });

  if (isCorrect) {
    btn.classList.add('correct');
    feedback.className = 'quiz-feedback show correct';
    feedback.innerHTML = '<strong>✓ Betul!</strong> ' + (explanation || '');
  } else {
    btn.classList.add('wrong');
    feedback.className = 'quiz-feedback show wrong';
    feedback.innerHTML = '<strong>✗ Kurang tepat.</strong> ' + (explanation || '');
  }
}

function resetQuiz(btn) {
  const quiz = btn.closest('.quiz');
  quiz.querySelectorAll('.quiz-option').forEach(opt => {
    opt.disabled = false;
    opt.classList.remove('correct', 'wrong');
  });
  const feedback = quiz.querySelector('.quiz-feedback');
  if (feedback) feedback.className = 'quiz-feedback';
}

/* ====== MOBILE MENU ====== */
function initMobileMenu() {
  const toggle = document.getElementById('menuToggle');
  const links = document.getElementById('navLinks');
  if (!toggle || !links) return;

  toggle.addEventListener('click', () => links.classList.toggle('open'));
  links.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => links.classList.remove('open'));
  });
}

/* ====== SIDEBAR (mobile toggle + active highlight on scroll) ====== */
function initSidebar() {
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
      const isOpen = sidebar.classList.contains('open');
      sidebarToggle.innerHTML = isOpen
        ? 'Sembunyikan daftar <span>▲</span>'
        : 'Daftar materi <span>▼</span>';
    });
  }

  // Highlight active lesson saat di-scroll
  const lessons = document.querySelectorAll('.lesson[id]');
  const links = document.querySelectorAll('.sidebar a[href^="#"]');

  if (lessons.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const id = entry.target.id;
        links.forEach(link => {
          link.classList.toggle('active', link.getAttribute('href') === '#' + id);
        });
      }
    });
  }, {
    rootMargin: '-20% 0px -70% 0px',
    threshold: 0
  });

  lessons.forEach(l => observer.observe(l));
}

/* ====== SCROLL TO TOP ====== */
function initScrollTop() {
  const btn = document.getElementById('scrollTop');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 600);
  });
  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/* ====== INITIALIZE ====== */
document.addEventListener('DOMContentLoaded', () => {
  initMarkButtons();
  initMobileMenu();
  initSidebar();
  initScrollTop();
  updateHomeProgress();

  // Highlight initial sidebar berdasarkan progress
  const topicEl = document.body.dataset.topic;
  if (topicEl) updateSidebarProgress(topicEl);
});
