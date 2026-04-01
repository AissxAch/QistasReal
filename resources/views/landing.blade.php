<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="قسطاس - نظام متكامل لإدارة مكاتب المحاماة. إدارة القضايا والعملاء والجلسات في منصة واحدة.">
  <title>قسطاس | نظام إدارة مكاتب المحاماة</title>
  <link rel="stylesheet" href="{{ asset('assets/marketing/styles.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWix+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkR4j8tbt4i0+M0Jq4W3f9Q1KXrY9+R4xP2g==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <a href="#home" class="nav-logo">
        <img src="{{ asset('assets/logo.png') }}" alt="شعار قسطاس" class="logo-image" width="40" height="40">
        <span class="logo-text">قسطاس</span>
      </a>

      <button class="nav-toggle" id="navToggle" aria-label="فتح القائمة">
        <span></span><span></span><span></span>
      </button>

      <ul class="nav-links" id="navLinks">
        <li><a href="#home" class="nav-link active">الرئيسية</a></li>
        <li><a href="#features" class="nav-link">المميزات</a></li>
        <li><a href="#how" class="nav-link">كيف يعمل</a></li>
        <li><a href="#pricing" class="nav-link">الأسعار</a></li>
        <li><a href="#contact" class="nav-link">تواصل</a></li>
      </ul>

      <a href="{{ route('login') }}" class="btn btn-outline-white nav-cta">تسجيل الدخول</a>
      <a href="{{ route('register') }}" class="btn btn-primary nav-cta">ابدأ الآن</a>
    </div>
  </nav>

  <main>

    <!-- ===== HERO ===== -->
    <section class="hero" id="home">
      <div class="hero-bg">
        <div class="hero-logo-watermark" aria-hidden="true"></div>
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-grid"></div>
      </div>

      <div class="container hero-container">
        <div class="hero-content" data-animate="fade-up">
          <div class="hero-badge">
            <i class="fa-solid fa-star" aria-hidden="true"></i>
            الأول في إدارة مكاتب المحاماة بالجزائر
          </div>
          <h1 class="hero-title">
            نظام متكامل لإدارة<br>
            <span class="text-gold">مكاتب المحاماة</span>
          </h1>
          <p class="hero-subtitle">
            إدارة القضايا، العملاء، الجلسات، والمهام في منصة واحدة — مصممة خصيصًا لمتطلبات المحامي الجزائري.
          </p>
          <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn btn-gold btn-lg">
              <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
              جرّب مجانًا
            </a>
            <a href="#how" class="btn btn-outline-white btn-lg">
              <i class="fa-regular fa-circle-play" aria-hidden="true"></i>
              شاهد العرض
            </a>
          </div>
          <div class="hero-stats">
            <div class="hero-stat">
              <strong>اضافة اكثر من 200</strong>
              <span>محامٍ نشط في نفس المكتب او المؤسسة</span>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
              <strong>اكثر من 5000</strong>
              <span>قضية مُدارة</span>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
              <strong>98%</strong>
              <span>رضا المستخدمين</span>
            </div>
          </div>
        </div>

        <!-- Dashboard Mockup -->
        <div class="hero-mockup" data-animate="fade-left">
          <div class="mockup-browser">
            <div class="browser-bar">
              <div class="browser-dots">
                <span class="dot dot-red"></span>
                <span class="dot dot-yellow"></span>
                <span class="dot dot-green"></span>
              </div>
              <div class="browser-url">
                <i class="fa-solid fa-lock" aria-hidden="true"></i>
                qistas.app/dashboard
              </div>
            </div>
            <div class="mockup-app">
              <aside class="app-sidebar">
                <div class="sidebar-brand">م</div>
                <nav class="sidebar-nav">
                  <a href="#" class="sidebar-item active" title="لوحة التحكم">
                    <i class="fa-solid fa-table-cells-large" aria-hidden="true"></i>
                  </a>
                  <a href="#" class="sidebar-item" title="القضايا">
                    <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                  </a>
                  <a href="#" class="sidebar-item" title="العملاء">
                    <i class="fa-solid fa-users" aria-hidden="true"></i>
                  </a>
                  <a href="#" class="sidebar-item" title="الجلسات">
                    <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
                  </a>
                  <a href="#" class="sidebar-item" title="المهام">
                    <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                  </a>
                </nav>
              </aside>
              <div class="app-main">
                <div class="app-topbar">
                  <h4>لوحة التحكم</h4>
                  <div class="topbar-actions">
                    <span class="notif-dot">
                      <i class="fa-regular fa-bell" aria-hidden="true"></i>
                    </span>
                    <div class="user-avatar">م.أ</div>
                  </div>
                </div>

                <div class="stats-row">
                  <div class="stat-card">
                    <div class="stat-icon stat-blue">
                      <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                    </div>
                    <div>
                      <div class="stat-value">47</div>
                      <div class="stat-label">القضايا النشطة</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-icon stat-gold">
                      <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                    </div>
                    <div>
                      <div class="stat-value">3</div>
                      <div class="stat-label">جلسات اليوم</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-icon stat-green">
                      <i class="fa-solid fa-user-group" aria-hidden="true"></i>
                    </div>
                    <div>
                      <div class="stat-value">128</div>
                      <div class="stat-label">العملاء</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-icon stat-orange">
                      <i class="fa-solid fa-clipboard-check" aria-hidden="true"></i>
                    </div>
                    <div>
                      <div class="stat-value">12</div>
                      <div class="stat-label">مهام معلقة</div>
                    </div>
                  </div>
                </div>

                <div class="charts-area">
                  <div class="chart-card">
                    <div class="chart-title">القضايا الأسبوعية</div>
                    <div class="bar-chart">
                      <div class="bar-wrap"><div class="bar-fill" style="--h:60%"></div><span>أح</span></div>
                      <div class="bar-wrap"><div class="bar-fill" style="--h:80%"></div><span>إث</span></div>
                      <div class="bar-wrap"><div class="bar-fill" style="--h:45%"></div><span>ث</span></div>
                      <div class="bar-wrap active"><div class="bar-fill" style="--h:95%"></div><span>أر</span></div>
                      <div class="bar-wrap"><div class="bar-fill" style="--h:70%"></div><span>خ</span></div>
                      <div class="bar-wrap"><div class="bar-fill" style="--h:55%"></div><span>ج</span></div>
                      <div class="bar-wrap"><div class="bar-fill" style="--h:35%"></div><span>س</span></div>
                    </div>
                  </div>
                  <div class="sessions-mini">
                    <div class="chart-title">جلسات قادمة</div>
                    <div class="session-item">
                      <div class="session-date">اليوم <span>10:00</span></div>
                      <div class="session-info">قضية ميراث — محكمة سكيكدة</div>
                    </div>
                    <div class="session-item">
                      <div class="session-date">غدًا <span>14:30</span></div>
                      <div class="session-info">قضية عقارية — محكمة قالمة</div>
                    </div>
                    <div class="session-item">
                      <div class="session-date">الخميس <span>09:00</span></div>
                      <div class="session-info">قضية عمالية — مجلس الدولة</div>
                    </div>
                  </div>
                </div>

                <div class="cases-mini">
                  <div class="chart-title">آخر القضايا</div>
                  <div class="case-row">
                    <span class="case-badge badge-active">نشطة</span>
                    <span class="case-name">قضية ميراث — أحمد محمد</span>
                    <span class="case-date">12 أبر</span>
                  </div>
                  <div class="case-row">
                    <span class="case-badge badge-pending">معلقة</span>
                    <span class="case-name">قضية عقارية — شركة الأفق</span>
                    <span class="case-date">08 أبر</span>
                  </div>
                  <div class="case-row">
                    <span class="case-badge badge-active">نشطة</span>
                    <span class="case-name">قضية عمالية — فاطمة علي</span>
                    <span class="case-date">05 أبر</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== PROBLEMS & SOLUTIONS ===== -->
    <section class="section section-light" id="problems">
      <div class="container">
        <div class="section-header" data-animate="fade-up">
          <div class="section-tag">التحدي والحل</div>
          <h2>هل تواجه هذه المشكلات في مكتبك؟</h2>
          <p>كثير من المحامين يعانون من نفس العقبات اليومية — قسطاس صُمِّم ليحل كل واحدة منها.</p>
        </div>

        <div class="problems-grid" data-animate="fade-up">
          <!-- Problems -->
          <div class="problem-col">
            <div class="col-label col-label-problem">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
              التحديات
            </div>
            <div class="problem-card">
              <div class="problem-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
              <div>
                <h4>ضياع المواعيد والجلسات</h4>
                <p>فوات مواعيد الجلسات والمواعيد القانونية بسبب الاعتماد على الورق أو التقويم اليدوي.</p>
              </div>
            </div>
            <div class="problem-card">
              <div class="problem-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
              </div>
              <div>
                <h4>تشتت الملفات والمستندات</h4>
                <p>ملفات موزعة بين الحاسوب، البريد الإلكتروني، والأوراق — يصعب إيجادها عند الحاجة.</p>
              </div>
            </div>
            <div class="problem-card">
              <div class="problem-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
              </div>
              <div>
                <h4>ضعف متابعة العملاء</h4>
                <p>عدم القدرة على تتبع حالة القضايا وإعلام العملاء بالتطورات بشكل منتظم ومنظم.</p>
              </div>
            </div>
          </div>

          <!-- Arrow -->
          <div class="problems-arrow">
            <div class="arrow-circle">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
            <span>قسطاس</span>
          </div>

          <!-- Solutions -->
          <div class="solution-col">
            <div class="col-label col-label-solution">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              الحلول
            </div>
            <div class="solution-card">
              <div class="solution-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              </div>
              <div>
                <h4>تنبيهات ذكية تلقائية</h4>
                <p>إشعارات فورية قبل كل جلسة وموعد — لن يفوتك شيء مهما كان مشغولًا.</p>
              </div>
            </div>
            <div class="solution-card">
              <div class="solution-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
              </div>
              <div>
                <h4>ملف موحد لكل قضية</h4>
                <p>جميع المستندات والمهام والمراسلات في مكان واحد — منظمة وقابلة للبحث الفوري.</p>
              </div>
            </div>
            <div class="solution-card">
              <div class="solution-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="23 11 17 11 20 8"/><polyline points="23 11 17 11 20 14"/></svg>
              </div>
              <div>
                <h4>بوابة تواصل مع العملاء</h4>
                <p>تحديثات تلقائية لحالة القضايا تُرسل للعملاء — علاقة أكثر ثقة واحترافية.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section class="section section-white" id="features">
      <div class="container">
        <div class="section-header" data-animate="fade-up">
          <div class="section-tag">المميزات</div>
          <h2>كل ما تحتاجه في مكتبك القانوني</h2>
          <p>أدوات احترافية مدمجة في منصة واحدة — مصممة لتوفير وقتك وزيادة إنتاجيتك.</p>
        </div>

        <div class="features-grid">
          <div class="feature-card" data-animate="fade-up" data-delay="0">
            <div class="feature-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <h3>إدارة القضايا</h3>
            <p>تتبع كل قضية من فتحها حتى إغلاقها — الحالة، الوثائق، الأطراف، والتطورات في ملف موحد.</p>
            <div class="feature-link">
              اكتشف المزيد
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
          </div>

          <div class="feature-card" data-animate="fade-up" data-delay="100">
            <div class="feature-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h3>إدارة العملاء</h3>
            <p>سجل موحد لكل عميل يتضمن بياناته الشخصية، قضاياه، ومراسلاته — وصول فوري في أي وقت.</p>
            <div class="feature-link">
              اكتشف المزيد
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
          </div>

          <div class="feature-card" data-animate="fade-up" data-delay="200">
            <div class="feature-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="12" cy="16" r="2" fill="currentColor"/></svg>
            </div>
            <h3>إدارة الجلسات</h3>
            <p>جدول جلساتك القضائية مع تنبيهات تلقائية — لا يفوتك موعد في المحكمة أبدًا.</p>
            <div class="feature-link">
              اكتشف المزيد
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
          </div>

          <div class="feature-card" data-animate="fade-up" data-delay="0">
            <div class="feature-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <h3>إدارة المهام</h3>
            <p>أنشئ مهام مرتبطة بالقضايا وأسنِدها لأعضاء فريقك مع متابعة حالة الإنجاز بوضوح.</p>
            <div class="feature-link">
              اكتشف المزيد
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
          </div>

          <div class="feature-card" data-animate="fade-up" data-delay="100">
            <div class="feature-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/><circle cx="18" cy="8" r="3" fill="#C9A547" stroke="none"/></svg>
            </div>
            <h3>الإشعارات الذكية</h3>
            <p>تنبيهات فورية عند اقتراب مواعيد الجلسات، انتهاء مهل الطعون، وتحديثات القضايا.</p>
            <div class="feature-link">
              اكتشف المزيد
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
          </div>

          <div class="feature-card" data-animate="fade-up" data-delay="200">
            <div class="feature-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3>إدارة الصلاحيات</h3>
            <p>حدد صلاحيات كل عضو في الفريق — مساعد، محامٍ، أو مدير — بنظام أدوار مرن وآمن.</p>
            <div class="feature-link">
              اكتشف المزيد
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== HOW IT WORKS / PRODUCT PREVIEW ===== -->
    <section class="section section-dark" id="how">
      <div class="container">
        <div class="section-header section-header-light" data-animate="fade-up">
          <div class="section-tag section-tag-gold">كيف يعمل</div>
          <h2>ثلاث خطوات وتبدأ إدارة مكتبك</h2>
          <p>لا حاجة لتدريب طويل — قسطاس سهل الاستخدام ويمكنك البدء في دقائق.</p>
        </div>

        <div class="steps-row" data-animate="fade-up">
          <div class="step-card">
            <div class="step-num">01</div>
            <div class="step-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h4>أنشئ حسابك</h4>
            <p>سجل في دقيقتين، وأضف معلومات مكتبك وأعضاء فريقك.</p>
          </div>
          <div class="step-connector">
            <svg viewBox="0 0 40 12" fill="none"><path d="M0 6h36M30 1l6 5-6 5" stroke="#C9A547" stroke-width="2" stroke-linecap="round"/></svg>
          </div>
          <div class="step-card">
            <div class="step-num">02</div>
            <div class="step-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </div>
            <h4>أضف قضاياك وعملاءك</h4>
            <p>استورد بياناتك الموجودة أو ابدأ بإدخال قضاياك وعملائك مباشرة.</p>
          </div>
          <div class="step-connector">
            <svg viewBox="0 0 40 12" fill="none"><path d="M0 6h36M30 1l6 5-6 5" stroke="#C9A547" stroke-width="2" stroke-linecap="round"/></svg>
          </div>
          <div class="step-card">
            <div class="step-num">03</div>
            <div class="step-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <h4>ابدأ إدارة مكتبك</h4>
            <p>تابع كل شيء من لوحة تحكم واحدة وركز على ما يهم — مهنتك القانونية.</p>
          </div>
        </div>

        <!-- Product Preview -->
        <div class="preview-section" data-animate="fade-up">
          <div class="preview-tabs">
            <button class="preview-tab active" data-tab="dashboard">لوحة التحكم</button>
            <button class="preview-tab" data-tab="clients">العملاء</button>
            <button class="preview-tab" data-tab="cases">القضايا</button>
            <button class="preview-tab" data-tab="sessions">الجلسات</button>
          </div>

          <div class="preview-frame">
            <!-- Dashboard Panel -->
            <div class="preview-panel active" id="panel-dashboard">
              <div class="preview-topbar">
                <span>لوحة التحكم</span>
                <div class="preview-actions">
                  <span class="p-btn">+ إضافة قضية</span>
                  <div class="p-avatar">م.أ</div>
                </div>
              </div>
              <div class="preview-stats">
                <div class="p-stat"><strong>47</strong><span>قضايا نشطة</span></div>
                <div class="p-stat p-stat-gold"><strong>3</strong><span>جلسات اليوم</span></div>
                <div class="p-stat"><strong>128</strong><span>عميل</span></div>
                <div class="p-stat"><strong>12</strong><span>مهام معلقة</span></div>
              </div>
              <div class="preview-body">
                <div class="p-card">
                  <div class="p-card-title">نشاط الأسبوع</div>
                  <div class="p-bar-chart">
                    <div class="p-bar" style="--h:55%"><span>أح</span></div>
                    <div class="p-bar" style="--h:75%"><span>إث</span></div>
                    <div class="p-bar" style="--h:40%"><span>ث</span></div>
                    <div class="p-bar p-bar-gold" style="--h:90%"><span>أر</span></div>
                    <div class="p-bar" style="--h:65%"><span>خ</span></div>
                    <div class="p-bar" style="--h:50%"><span>ج</span></div>
                    <div class="p-bar" style="--h:30%"><span>س</span></div>
                  </div>
                </div>
                <div class="p-card">
                  <div class="p-card-title">آخر القضايا</div>
                  <div class="p-list">
                    <div class="p-list-item"><span class="badge-a">نشطة</span><span>قضية ميراث — أحمد محمد</span><span class="p-date">12 أبر</span></div>
                    <div class="p-list-item"><span class="badge-p">معلقة</span><span>قضية عقارية — شركة الأفق</span><span class="p-date">08 أبر</span></div>
                    <div class="p-list-item"><span class="badge-a">نشطة</span><span>قضية عمالية — فاطمة علي</span><span class="p-date">05 أبر</span></div>
                    <div class="p-list-item"><span class="badge-c">مغلقة</span><span>قضية تجارية — مؤسسة الشروق</span><span class="p-date">02 أبر</span></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Clients Panel -->
            <div class="preview-panel" id="panel-clients">
              <div class="preview-topbar">
                <span>العملاء</span>
                <div class="preview-actions">
                  <span class="p-btn">+ عميل جديد</span>
                </div>
              </div>
              <div class="p-search"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg> بحث عن عميل...</div>
              <div class="clients-grid">
                <div class="client-card">
                  <div class="c-avatar" style="--c:#3B82F6">أ.م</div>
                  <div class="c-info">
                    <strong>أحمد محمد</strong>
                    <span>+213 555 123 456</span>
                    <span class="c-cases">3 قضايا نشطة</span>
                  </div>
                </div>
                <div class="client-card">
                  <div class="c-avatar" style="--c:#10B981">ش.أ</div>
                  <div class="c-info">
                    <strong>شركة الأفق</strong>
                    <span>+213 555 789 012</span>
                    <span class="c-cases">1 قضية نشطة</span>
                  </div>
                </div>
                <div class="client-card">
                  <div class="c-avatar" style="--c:#F59E0B">ف.ع</div>
                  <div class="c-info">
                    <strong>فاطمة علي</strong>
                    <span>+213 555 345 678</span>
                    <span class="c-cases">2 قضيتان نشطتان</span>
                  </div>
                </div>
                <div class="client-card">
                  <div class="c-avatar" style="--c:#8B5CF6">م.ش</div>
                  <div class="c-info">
                    <strong>مؤسسة الشروق</strong>
                    <span>+213 555 901 234</span>
                    <span class="c-cases">4 قضايا</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Cases Panel -->
            <div class="preview-panel" id="panel-cases">
              <div class="preview-topbar">
                <span>القضايا</span>
                <div class="preview-actions">
                  <span class="p-btn">+ قضية جديدة</span>
                </div>
              </div>
              <div class="p-filters">
                <span class="p-filter active">الكل (47)</span>
                <span class="p-filter">نشطة (32)</span>
                <span class="p-filter">معلقة (10)</span>
                <span class="p-filter">مغلقة (5)</span>
              </div>
              <div class="p-cases-list">
                <div class="p-case-row">
                  <div class="p-case-info">
                    <span class="badge-a">نشطة</span>
                    <div>
                      <strong>قضية ميراث رقم 2024/125</strong>
                      <span>العميل: أحمد محمد · محكمة سكيكدة</span>
                    </div>
                  </div>
                  <span class="p-date">جلسة: 15 أبر</span>
                </div>
                <div class="p-case-row">
                  <div class="p-case-info">
                    <span class="badge-p">معلقة</span>
                    <div>
                      <strong>قضية عقارية رقم 2024/089</strong>
                      <span>العميل: شركة الأفق · محكمة قالمة</span>
                    </div>
                  </div>
                  <span class="p-date">جلسة: 22 أبر</span>
                </div>
                <div class="p-case-row">
                  <div class="p-case-info">
                    <span class="badge-a">نشطة</span>
                    <div>
                      <strong>قضية عمالية رقم 2024/201</strong>
                      <span>العميل: فاطمة علي · مجلس الدولة</span>
                    </div>
                  </div>
                  <span class="p-date">جلسة: 18 أبر</span>
                </div>
                <div class="p-case-row">
                  <div class="p-case-info">
                    <span class="badge-c">مغلقة</span>
                    <div>
                      <strong>قضية تجارية رقم 2024/067</strong>
                      <span>العميل: مؤسسة الشروق · محكمة التجارة</span>
                    </div>
                  </div>
                  <span class="p-date">أُغلقت: 02 أبر</span>
                </div>
              </div>
            </div>

            <!-- Sessions Panel -->
            <div class="preview-panel" id="panel-sessions">
              <div class="preview-topbar">
                <span>الجلسات</span>
                <div class="preview-actions">
                  <span class="p-btn">+ جلسة جديدة</span>
                </div>
              </div>
              <div class="sessions-timeline">
                <div class="timeline-day">
                  <div class="timeline-date">اليوم - الأربعاء 12 أبريل</div>
                  <div class="timeline-item t-today">
                    <div class="t-time">10:00</div>
                    <div class="t-content">
                      <strong>قضية ميراث 2024/125</strong>
                      <span>محكمة سكيكدة · القاضي: محمد بن علي</span>
                      <span class="t-client">الموكل: أحمد محمد</span>
                    </div>
                    <span class="t-badge t-now">الآن</span>
                  </div>
                  <div class="timeline-item">
                    <div class="t-time">14:30</div>
                    <div class="t-content">
                      <strong>قضية عمالية 2024/201</strong>
                      <span>مجلس الدولة · الغرفة 3</span>
                      <span class="t-client">الموكلة: فاطمة علي</span>
                    </div>
                    <span class="t-badge t-upcoming">قريبًا</span>
                  </div>
                </div>
                <div class="timeline-day">
                  <div class="timeline-date">الخميس 13 أبريل</div>
                  <div class="timeline-item">
                    <div class="t-time">09:00</div>
                    <div class="t-content">
                      <strong>قضية عقارية 2024/089</strong>
                      <span>محكمة قالمة · القاضي: علي حمداني</span>
                      <span class="t-client">الموكل: شركة الأفق</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== BENEFITS ===== -->
    <section class="section section-white" id="benefits">
      <div class="container">
        <div class="benefits-layout">
          <div class="benefits-text" data-animate="fade-right">
            <div class="section-tag">الفوائد</div>
            <h2>لماذا يختار المحامون قسطاس؟</h2>
            <p>منصة مصممة بعمق لفهم احتياجات المكتب القانوني الجزائري — ليست مجرد أداة، بل شريك نجاح.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-md">ابدأ تجربتك المجانية</a>
          </div>
          <div class="benefits-list" data-animate="fade-left">
            <div class="benefit-item">
              <div class="benefit-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
              </div>
              <div>
                <h4>رفع الإنتاجية بنسبة 40%</h4>
                <p>وفّر ساعات من العمل اليدوي يوميًا بفضل الأتمتة الذكية وإدارة المهام المتكاملة.</p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
              </div>
              <div>
                <h4>تنظيم شامل للعمل</h4>
                <p>كل شيء في مكانه الصحيح — قضاياك، عملاؤك، وجلساتك منظمة ويسهل الوصول إليها.</p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
              <div>
                <h4>تقليل الأخطاء القانونية</h4>
                <p>التنبيهات التلقائية والمتابعة الدقيقة تقلل من الأخطاء الإجرائية ونسيان المواعيد.</p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div>
                <h4>حماية البيانات والسرية</h4>
                <p>تشفير كامل للبيانات واتصال آمن — سرية موكليك ومعلوماتك القانونية مضمونة.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== PRICING ===== -->
    <section class="section section-light" id="pricing">
      <div class="container">
        <div class="section-header" data-animate="fade-up">
          <div class="section-tag">الأسعار</div>
          <h2>خطة مناسبة لكل مكتب</h2>
          <p>ابدأ مجانًا ووسّع خطتك مع نمو مكتبك — بدون عقود طويلة الأمد.</p>
        </div>

        <div class="pricing-grid" data-animate="fade-up">
          <!-- Basic -->
          <div class="pricing-card">
            <div class="pricing-header">
              <div class="pricing-plan">أساسي</div>
              <div class="pricing-price">
                <span class="price-amount">2,500</span>
                <span class="price-currency">دج / شهر</span>
              </div>
              <p>مثالي للمحامي المستقل.</p>
            </div>
            <ul class="pricing-features">
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> حتى 2 مستخدمين</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> 50 قضية نشطة</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> إدارة العملاء</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> جدول الجلسات</li>
              <li class="disabled"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> التقارير المتقدمة</li>
              <li class="disabled"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> دعم الأولوية</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-outline-navy btn-full">ابدأ مجانًا</a>
          </div>

          <!-- Pro (Recommended) -->
          <div class="pricing-card pricing-featured">
            <div class="pricing-badge">موصى به</div>
            <div class="pricing-header">
              <div class="pricing-plan">احترافي</div>
              <div class="pricing-price">
                <span class="price-amount">8,000</span>
                <span class="price-currency">دج / شهر</span>
              </div>
              <p>للمكاتب التي تسعى للنمو.</p>
            </div>
            <ul class="pricing-features">
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> حتى 5 مستخدمين</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> قضايا غير محدودة</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> إدارة العملاء الكاملة</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> جدول الجلسات + تنبيهات</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> التقارير المتقدمة</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> دعم الأولوية</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-gold btn-full">ابدأ مجانًا</a>
          </div>

          <!-- Enterprise -->
          <div class="pricing-card">
            <div class="pricing-header">
              <div class="pricing-plan">مؤسسي</div>
              <div class="pricing-price">
                <span class="price-amount">تواصل</span>
                <span class="price-currency">سعر مخصص</span>
              </div>
              <p>لمكاتب المحاماة الكبيرة والشركات القانونية.</p>
            </div>
            <ul class="pricing-features">
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> مستخدمون غير محدودون</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> كل مميزات الخطة الاحترافية</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> تكامل مع أنظمة خارجية</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> خادم مخصص وأمان معزز</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> تدريب وإعداد متخصص</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> مدير حساب مخصص</li>
            </ul>
            <a href="#contact" class="btn btn-outline-navy btn-full">تواصل معنا</a>
          </div>
        </div>

        <p class="pricing-note">✓ جميع الخطط تشمل 7 أيام تجريبية مجانية</p>
      </div>
    </section>

    <!-- ===== CONTACT ===== -->
    <section class="section section-white" id="contact">
      <div class="container">
        <div class="contact-layout">
          <div class="contact-info" data-animate="fade-right">
            <div class="section-tag">تواصل معنا</div>
            <h2>نحن هنا لمساعدتك</h2>
            <p>هل لديك سؤال، تريد عرضًا توضيحيًا، أو تحتاج مساعدة في الإعداد؟ تواصل معنا وسنرد خلال 24 ساعة.</p>

            <div class="contact-details">
              <a href="mailto:achouri.aissa@outlook.com" class="contact-item">
                <div class="contact-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div>
                  <span>البريد الإلكتروني</span>
                  <strong>achouri.aissa@outlook.com</strong>
                </div>
              </a>
              <a href="https://www.linkedin.com/in/aissaach" target="_blank" rel="noopener" class="contact-item">
                <div class="contact-icon">
                  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                </div>
                <div>
                  <span>لينكدإن</span>
                  <strong>linkedin.com/in/aissaach</strong>
                </div>
              </a>
            </div>
          </div>

          <div class="contact-form-wrap" data-animate="fade-left">
            <form class="contact-form" id="contactForm" novalidate>
              <div class="form-group">
                <label for="name">الاسم الكامل</label>
                <input type="text" id="name" name="name" placeholder="أدخل اسمك الكامل" autocomplete="name">
                <span class="field-error" id="nameError"></span>
              </div>
              <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" placeholder="achouri.aissa@outlook.com" autocomplete="email" dir="ltr">
                <span class="field-error" id="emailError"></span>
              </div>
              <div class="form-group">
                <label for="message">رسالتك</label>
                <textarea id="message" name="message" rows="4" placeholder="أخبرنا كيف يمكننا مساعدتك..."></textarea>
                <span class="field-error" id="messageError"></span>
              </div>
              <button type="submit" class="btn btn-primary btn-full btn-submit">
                <span class="btn-text">إرسال الرسالة</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              </button>
              <div class="form-success" id="formSuccess">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                تم إرسال رسالتك بنجاح! سنتواصل معك قريبًا.
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="footer">
    <div class="container">
      <div class="footer-main">
        <div class="footer-brand">
          <a href="#home" class="nav-logo footer-logo">
            <img src="{{ asset('assets/logo.png') }}" alt="شعار قسطاس" class="logo-image footer-logo-image" width="36" height="36">
            <span class="logo-text">قسطاس</span>
          </a>
          <p>نظام متكامل لإدارة مكاتب المحاماة — مصمم للمحامي الجزائري الحديث.</p>
          <a href="https://www.linkedin.com/in/aissaach" target="_blank" rel="noopener" class="social-link" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
          </a>
        </div>

        <div class="footer-links">
          <div class="footer-col">
            <h5>المنتج</h5>
            <a href="#features">المميزات</a>
            <a href="#how">كيف يعمل</a>
            <a href="#pricing">الأسعار</a>
          </div>
          <div class="footer-col">
            <h5>الشركة</h5>
            <a href="#contact">تواصل معنا</a>
            <a href="mailto:achouri.aissa@outlook.com">الدعم التقني</a>
          </div>
          <div class="footer-col">
            <h5>قانوني</h5>
            <a href="#">سياسة الخصوصية</a>
            <a href="#">شروط الاستخدام</a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© 2025 قسطاس. جميع الحقوق محفوظة.</p>
        <p>صُنع بـ ♥ في الجزائر</p>
      </div>
    </div>
  </footer>

  <script src="{{ asset('assets/marketing/script.js') }}"></script>
</body>
</html>
