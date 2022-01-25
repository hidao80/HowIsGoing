# README

[![MIT license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE.md)
[![jQuery](https://img.shields.io/badge/jQuery-1.12-blue.svg)](https://nodejs.org/ja/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.1.1-blue.svg)](https://nodejs.org/ja/)
![hidao quality](https://img.shields.io/badge/hidao-quality-orange.svg)

## 『How is going』とは

社内でPHPの動くWebサーバ向けの進捗共有システムです。
次のような特徴があります。

- **PHPの動くWebサーバがあれば動作します**。
- データベースにはSqlite3を利用しており、**別途DBMSをインストールする必要がありません**。
- **セキュリティには配慮されていない**ので、公開サーバで使うことには向きません。
- チーム全体の進捗を確認する画面と個人の進捗を確認・入力する画面があり、朝会や日中の進捗管理が簡単に行えます。
- **タスクの数と進捗を表すランプにより、進捗度合いがひと目で確認できます**。
- ブラウザのロケールに応じて**自動的に表示言語を切り替えることができます**。現在は日本語と英語に対応しています。
- 進捗状況やタスクのメモを入力したあと、**3秒後に自動保存されます**。

## スクリーンショット

- タスクの入力
  ![Enter task](ss/ss1.png)
- 進捗管理
  ![View status](ss/ss2.png)
- チームの進捗一覧
  ![List of team status](ss/ss3.png)

## 未実装の機能

- [ ] タスクの削除機能
- [ ] ログイン機能