# AI Prompt Log for Task Management AI Project
This document logs the sequence of prompts used to guide the AI coding agent (Claude) in building the Task Management AI system. The prompts are written from the perspective of a senior software engineer, demonstrating thoughtful guidance, architectural decisions, and corrections while leveraging the AI for most of the code generation.
The project is a Task Management API with AI-powered task breakdown using Anthropic Claude, built with Laravel 12 (PHP 8.2+), React 18 + Vite, following best practices like service patterns, strict typing, enums, etc.

# Prompt 1

Act as a Senior Backend Developer and create a Task Management AI system with the required things to keep in mind are below but make sure to provide the step by step code and commands to fulfill each and every step and feature. Also make sure to follow all the standards of php 8.1 and laravel 12 latest version and React 18 with Vite for the frontend, follow a Service Pattern. Initialize the project requirements and:
Use Strict Typing throughout, Use enum for status instead of string
Set up the Models and its respective Database schema for tasks, users, and subtasks etc.
Implement Migrations with proper indexing and foreign key constraints, timestamps, softDeletes where appropriate.
Request classes and custom rules for validation, resources for the responses, Events and the respective Listeners, notifications and queues, custom policies and console commands, task specific Exception classes,facades to include the services. Use swagger for the api documentation

# Prompt 2

Review the entire project. List any missing files/paths (e.g., config/sanctum.php if not published, queue.php config, broadcasting.php, .env.example updates so README install steps for both backend/frontend, API endpoint docs

# Frontend Prompt 3

Design a modular React frontend that interfaces with our Task Management API. Implement a Service Layer for API calls using Axios, use Custom Hooks for state management and data fetching, and ensure the UI is state-driven to handle loading, success, and backend validation errors gracefully. Prioritize a clean component hierarchy and use a Context or Store pattern to manage global task state