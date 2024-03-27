# Performance profiler

## Introduction

This module allows to monitor the time and memory amount, used by PHP to process the request or render the page.

PHP and DB benchmarks (MySQL like only for now) are included. It allows to compare pure performance of different environments.

This one was inspired by great module <a href="https://www.drupal.org/project/memory_profiler">Memory profiler</a>.

## Requirements

This module does not require any other modules.

## Installation

The module can be installed via Composer and enabled as per normal Drupal module installation.

## Configuration

Once enabled there are few things that can be configured.

Go to `/admin/config/development/performance-profiler`, here you can configure:

- Appearance
  - Log statistics into watchdog: enable to have statistics logged into watchdog system.
  - Log statistics into Toolbar: available only if core Toolbar module installed, if enabled - new item with access to statistic will be added into toolbar.
  - Log statistics into Popup: if enabled - statistic printed into special popup at right bottom corner of the screen.
  - Log statistics for anonymous user: if enabled - statistics of anonymous requests will be collected, to access statistics need to allow anonymous "access performance profiler" or enable "Log statistics into watchdog".
- Log entries
  - Log Database queries: enable to collect information about Database queries (like amount and time of read/write requests, top 20 request)
  - Log self AJAX query: enable to include performance profiler AJAX request (`/performance-profiler/ajax/performance-data`) into statistics. Will be included only if next setting "Log AJAX queries" enabled.
  - Log AJAX queries: enable to include AJAX requests into statistics.
- Track
  - Min memory usage to track: enter number more than 0 to exclude requests, which are using less than specified amount of memory.
  - Min execution time to track: enter number more than 0 to exclude requests, which processed faster specified time.

## Usage

On every page load, module collecting information about PHP processing time, memory usage and database queries (if enabled) and store that information into watchdog (if enabled) or printing to the page (in popup or toolbar).
That allows to investigate performance of different pages, see what request and database queries executing on it.

It's possible to profile pure PHP or Database performance, it can be helpful to compare base performance of different environments.
Go to `/admin/config/development/performance-profiler/run` and run PHP or Database benchmark, it will execute some simple operations and print time/memory usage for them.

On the page `/admin/config/development/performance-profiler/database` you can execute some Database actions with hardcoded examples to check database performance.

## Troubleshooting

Post problems or feature requests to the [Drupal project issue queue](https://drupal.org/project/issues/performance_profiler).
