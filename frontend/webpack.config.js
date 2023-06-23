const path = require("path");
const devMode = process.env.NODE_ENV !== "production";
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

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
                exclude: /node_modules/,
                use: "babel-loader"
            },
            {
                test: /\.css$/,
                use: [
                    devMode ? "style-loader" : MiniCssExtractPlugin.loader,
                    "css-loader",
                ],
            },
        ]
    },
    plugins: [].concat(devMode ? [] : [new MiniCssExtractPlugin()]),
    devtool : 'inline-source-map'
}