const path = require("path");

module.exports = {
    entry: "./src",
    output: {
        path: path.resolve(__dirname, "./static/js"),
        filename: "bundle.js"
    },
    mode: "development",
    module: {
        rules: [
            {
                test: /\.js$/,
                use: "babel-loader"
            }
        ]
    }
}