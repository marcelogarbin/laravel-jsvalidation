module.exports = {
  debug: true,
  branch: 'master',
  plugins: [
    '@semantic-release/commit-analyzer',
    '@semantic-release/exec',
    ["@semantic-release/git", {
      "assets": ["public/**/*"],
      "message": "chore(release): ${nextRelease.version} [skip ci]"
    }],
    '@semantic-release/github'
  ],
  verifyConditions: [
    '@semantic-release/github',
  ],
  prepare: [
    {
      path: '@semantic-release/exec',
      cmd: 'gulp build'
    },
    '@semantic-release/git',
  ],
  publish: [
    '@semantic-release/github'
  ]
}