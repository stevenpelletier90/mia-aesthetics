export default {
  plugins: {
    autoprefixer: {
      grid: true,
      overrideBrowserslist: [
        "> 0.2%",
        "last 3 versions", 
        "not dead",
        "not IE 11"
      ]
    },
    cssnano: {
      preset: "default",
    },
  },
};
