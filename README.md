# Chip Code Detector Application

Aplicație PHP pentru detectare și comparare coduri alfanumerice de pe cipuri electronice.

## Funcționalități
- ✅ Detectare coduri din cameră (OCR)
- ✅ Comparare live cu baza de date
- ✅ Căutare manuală în text box
- ✅ Căutare pe Google API dacă nu se găsește codul
- ✅ Organizare pe categorii (din Excel)

## Structură Folder

```
catalog-/
├── data/
│   └── coduri.xlsx          ← Fișierul cu coduri (pune-l aici)
├── app/
│   ├── index.php
│   ├── config.php
│   ├── ocr.php
│   ├── database.php
│   └── search.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── camera.js
├── public/
│   └── upload/
└── README.md
```

## Setup

1. Pune fișierul `coduri.xlsx` în folderul `/data/`
2. Configurează Google API key în `app/config.php`
3. Accesează `app/index.php` în browser

## Requirements
- PHP 7.4+
- Extensie PHP: php-gd, php-curl
- Browser modern cu suport cameră (HTTPS)
