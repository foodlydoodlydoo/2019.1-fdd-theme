/* global WebFont */

import WebFont from 'webfontloader';

/** unused */

// 'Open Sans:300',
// 'Open Sans Condensed:300',
// 'Prata',
// 'Antic Didone',
// 'Assistant:300',
// 'Noto Serif TC:200',
// 'Rasa',
// 'Gafata:400',

WebFont.load({
  google: {
    families: [
      'Noto Serif KR:300',
      'Noto Serif:300', // as czech chars (imperfect) fallback
      'Spectral:200i',
      'Abel:400',
      'Oranienbaum',
      'Open Sans:400',
    ],
  },
});
